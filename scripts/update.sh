#!/bin/bash

CWD=`pwd`
TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
NAME=`basename "${TOPDIR}"`
CONFIGFILE="${HOME}/server-config/${NAME}"
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
if [[ -f "${CONFIGFILE}" ]]; then
    BRANCH=`grep ^branch "${CONFIGFILE}" | sed -e 's/^branch\s*=\s*//'`
fi

echo "Selected branch: ${BRANCH}"

# Get requested branch from origin if this is the first time

if [[ -z `git branch | grep ${BRANCH}` ]]; then
    git checkout -b ${BRANCH} origin/${BRANCH}
fi

# In case we hadn't already, make sure to switch to the requested branch

git checkout ${BRANCH}

# Update toplevel repository and submodules

git pull
git submodule update

# legalcode repo is special, always keep it at the latest rev so that
# legal team doesn't need to manually update the toplevel repo (since
# that requires command-line use)
#
# Note that we use the same branch as the toplevel if available

# cd docroot/legalcode
# if [[ ! -z `git branch -r | grep ${BRANCH}` ]]; then
#     if [[ -z `git branch | grep ${BRANCH}` ]]; then
# 	echo "Checking out legalcode branch ${BRANCH} from remote"
# 	git checkout -b ${BRANCH} origin/${BRANCH}
#     fi
# fi

# echo "Making sure legalcode is set to branch ${BRANCH}"
# git checkout ${BRANCH}

# echo "Updating legalcode"
# git pull

# cd ../..

# # Commit any update to legalcode submodule rev
# git commit -m "Update legalcode submodule to latest version" docroot/legalcode

# Another ugly hack - some WP plugins can't handle how we have it
# set-up (with wp-content outside of wordpress folder), so make sure
# those are symlinked in there

cd docroot/wordpress/wp-content/plugins
[[ -L collapsing-archives ]] || ln -s ../../../wp-content/plugins/collapsing-archives .
[[ -L wp-recaptcha ]] || ln -s ../../../wp-content/plugins/wp-recaptcha .
cd -

# Ugly hacks over - activate python env and carry on...

# if quick arg was suplied, exit now
if [[ $1 == "quick" || $2 == "quick" ]]; then
    exit
fi

source python_env/bin/activate

if [[ $1 == "update-l10n" || $2 == "update-l10n" ]]
then
    # Update l10n strings in i18n submodule and sync with Transifex/GitHub
    cd python_env/src/cc.i18n/

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
    git commit -m "Update i18n submodule with latest strings/translations" python_env/src/cc.i18n
    git push
fi

# In case translation files changed:
python_env/bin/compile_mo
python_env/bin/transstats
python_env/src/cc.i18n/bin/compile_mo
python_env/src/cc.i18n/bin/transstats

# Eek - how do we figure out if a restart is needed?
sudo /etc/init.d/apache2 restart

cd "${CWD}"
