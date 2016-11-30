#!/bin/bash

# Adapted from the outdated update.sh
# This script pulls translations and deploys them.
# If you are running this on the same machine as merge-translations.sh,
# run that before this.

# Move to project root
pushd "$( dirname "${BASH_SOURCE[0]}" )/.."

source python_env/bin/activate

pushd python_env/src/i18n/

git checkout master
# Get latest changes so we don't clash with remote HEAD 
git pull

# Back to project root
popd

# Build new translations, if any
python_env/bin/compile_mo
python_env/bin/transstats
python_env/bin/compile_mo
python_env/bin/transstats

# Back to directory we were called from
popd

# Should only do this if there were new translations.
/etc/init.d/apache2 restart
