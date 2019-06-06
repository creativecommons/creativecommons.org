#!/bin/bash
#
# This script:
# 1. Merges in new translations from Transifex
# 2. Commits and pushes them to the cc.i18n git repository.
# 3. Builds new translations (if any)
# 4. Restarts Apache2
#
# It's here rather than in cc.i18n repository because of its parentage.
#
# You will need a valid .transifexrc .
#
# And you'll need to make sure that you have a pushable checkout of cc.i18n:
#   functional remote:
#       git@github.com:creativecommons/cc.i18n.git
#   invalid remote:
#       https://github.com/creativecommons/cc.i18n.git
set -o errexit
set -o errtrace
set -o nounset

trap '_es=${?};
    printf "${0}: line ${LINENO}: \"${BASH_COMMAND}\"";
    printf " exited with a status of ${_es}\n";
    exit ${_es}' ERR

BASE="${0%/*}"

# Change directory to project root
pushd "${BASE}/.." >/dev/null

# Activate Python environment
set +o nounset
source python_env/bin/activate
set -o nounset

# Change directory to language repository
pushd python_env/src/cc.i18n/ >/dev/null

# Get latest changes so we don't clash with remote HEAD
git checkout -q master
git pull -q origin master

# Pull and commit new translations
tx -q pull -a --mode developer

# Refresh index and test for changes
git update-index -q --refresh
if git diff-index --quiet HEAD --
then
    # No changes

    # Change directory back to project root
    popd >/dev/null

    # change directory back to where script was called from
    popd >/dev/null

else
    # Changes

    # Error exit disabled because Languages added to Transifex will download
    # and this script shouldn't break pending their addition to the
    # repository
    set +o errexit
    git commit -q -a -m'Latest i18n updates from Transifex'
    set -o errexit
    git push -q origin master

    # Change directory back to project root
    popd >/dev/null

    # Build new translations (if any)
    python_env/bin/compile_mo
    python_env/bin/transstats
    python_env/bin/compile_mo
    python_env/bin/transstats

    # change directory back to where script was called from
    popd >/dev/null

    # Should only do this if there were new translations.
    sudo /usr/sbin/service apache2 restart
fi
