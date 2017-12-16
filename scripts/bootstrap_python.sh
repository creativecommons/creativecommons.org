#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

pushd ${TOPDIR}

#
# Set up Python env
#

pushd python_env

virtualenv -p python3 .
source bin/activate

git clone "https://github.com/creativecommons/cc.engine"

pushd cc.engine
#REMOVE ME WHEN READY!!!!
git checkout python3
python setup.py develop
popd

#
# compile_mo & transstats are needed by cc.engine at runtime, run them now
#

bin/compile_mo
bin/transstats

popd # to topdir

#
# Generate ccengine.fcgi
#

sed -e "s|@env_dir@|${TOPDIR}/python_env|" \
    < "python_env/bin/ccengine.fcgi.in" \
    > "python_env/bin/ccengine.fcgi"

chmod 755 python_env/bin/ccengine.fcgi

#
# Support the semantic web
#

ln -s ${TOPDIR}/python_env/src/cc.licenserdf \
   ${TOPDIR}/docroot/cc.licenserdf
ln -s ${TOPDIR}/docroot/cc.licenserdf/cc/licenserdf/rdf \
   ${TOPDIR}/docroot/rdf
ln -s ${TOPDIR}/docroot/cc.licenserdf/cc/licenserdf/licenses \
   ${TOPDIR}/docroot/license_rdf

popd # to original
