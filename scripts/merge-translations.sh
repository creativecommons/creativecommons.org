#!/bin/bash

# Adapted from the outdated update.sh in the creativecommons.org repo.
# This script merges in new translations from Transifex, committing and pusing
# them to the cc.i18n git repository.
# It's here rather than in cc.i18n because of its parentage.
# You will need a valid .transifexrc .
# And you'll need to make sure that you have a pushable checkout of i18n,
# ie:
#    git@github.com:creativecommons/cc.i18n.git
# not:
#    https://github.com/creativecommons/cc.i18n.git

# Move to project root
pushd "$( dirname "${BASH_SOURCE[0]}" )/.."

source python_env/bin/activate

pushd python_env/src/cc.i18n/

git checkout master
# Get latest changes so we don't clash with remote HEAD
git pull

# Pull and commit new translations
tx pull -a --mode developer
git commit -a -m "Latest i18n updates from Transifex"
git push origin master

# Back to project root
popd
