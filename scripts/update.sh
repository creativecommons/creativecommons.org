#!/bin/bash

CWD=`pwd`
TOPDIR="$(cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd)"
cd "${TOPDIR}"

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

# Eek - how do we figure out if a restart is needed?
sudo /etc/init.d/apache2 restart

cd "${CWD}"
