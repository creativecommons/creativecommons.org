#!/bin/bash

CWD=`pwd`
NAME=$( dirname "${BASH_SOURCE[0]}")
TOPDIR="$(cd "${NAME}/.." && pwd)"
cd "${TOPDIR}"

# This pretty hacky - should have update script be part of
# server-config repo, perhaps?
#
# But regardless - if ~/server-config exists and it has a file for our
# server name (assuming our dirname is our server name...), then
# assume it has some ini-style settings for us. In particular, the
# 'branch' setting will tell us which branch we should switch to.

# Find out if we should be using something other than the master
# branch (the default)

BRANCH=master
if [[ -d "~/server-config" && -f "~/server-config/${NAME}" ]]; then
    BRANCH=`grep ^branch "~/server-config/${NAME}" | sed -e 's/^branch\s*=\s*//'`
fi

# Get requested branch from origin if this is the first time

if [[ -z `git branch | grep ${BRANCH}` ]]; then
    git checkout -b ${BRANCH} origin/${BRANCH}
fi

# In case we hadn't already, make sure to switch to the requested branch

git checkout ${BRANCH}

# Update toplevel repository and submodules

git pull
git submodule update

source python_env/bin/activate

if [[ $1 == "update-l10n" ]]
then
    # Update l10n strings in i18n submodule and sync with Transifex/GitHub
    cd python_env/src/i18n/

    git checkout master # make sure we're on a branch, git submodules
    git pull            # have a bad habit of having a detached HEAD

    scripts/runcheckouts.sh
    scripts/extract.sh

    # Only commit if something substantial changed (not just the creation timestamp)
    if [[ `git diff cc/i18n/po/en/cc_org.po|grep '^\+'|wc -l` == '2' ]]
    then
        git checkout cc/i18n/po/en/cc_org.po
    else
        git commit -m "New strings extracted from sources" cc/i18n/po/en/cc_org.po
        tx push -s
    fi

    tx pull -a --mode developer
    git commit -a -m "Latest i18n updates from Transifex"

    git push

    # Update toplevel repository to point to latest i18n rev
    cd "${TOPDIR}"
    git commit -m "Update i18n submodule with latest strings/translations" python_env/src/i18n
    git push
fi

# In case translation files changed:
python_env/bin/compile_mo
python_env/bin/transstats
python_env/src/i18n/bin/compile_mo
python_env/src/i18n/bin/transstats

# Eek - how do we figure out if a restart is needed?
sudo /etc/init.d/apache2 restart

cd "${CWD}"
