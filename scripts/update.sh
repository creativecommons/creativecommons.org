#!/bin/bash

CWD=`pwd`
TOPDIR="$(cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd)"
cd "${TOPDIR}"

# Update toplevel repository and submodules

git pull
git submodule update

if [[ $1 == "update-l10n" ]]
then
    # Update l10n strings in i18n submodule and sync with Transifex/GitHub
    cd python_env/src/i18n/

    git checkout master # make sure we're on a branch, git submodules
                        # have a bad habit of having a detached HEAD

    scripts/runcheckouts.sh
    scripts/extract.sh

    tx push -s
    tx pull -a --mode=reviewed

    git commit -m "New strings extracted from sources" cc/i18n/po/en/cc_org.po
    git commit -a -m "Latest i18n updates from Transifex"
    git push

    # Update toplevel repository to point to latest i18n rev
    cd "${TOPDIR}"
    git commit -m "Update i18n submodule with latest strings/translations" python_env/src/i18n
fi

cd "${CWD}"
