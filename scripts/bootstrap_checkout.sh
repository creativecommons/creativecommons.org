#!/bin/bash

CUR=`pwd`
TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

cd ${TOPDIR}

git submodule init
git submodule update

cd python_env

sed -e "s|@env_dir@|${TOPDIR}/python_env|" < bin/ccengine.fcgi.in > bin/ccengine.fcgi
chmod 755 bin/ccengine.fcgi

virtualenv .
source bin/activate

for i in 'setuptools>=0.7' 'zope.interface>=3.8.0' Paste PasteDeploy PasteScript RDF
do
    pip install $i
done

# On Ubuntu, virtualenv setups don't "see" dist-packages, which is
# where Ubuntu-packaged modules go. This works around that problem:

echo "/usr/lib/python2.7/dist-packages/" > lib/python2.7/site-packages/dist-packages.pth

cd src

for i in i18n license.rdf cc.license cc.engine
do
    cd $i
    python bootstrap.py -v 2.1.1
    bin/buildout
    python setup.py develop
    cd ..
done

cd .. # python_env

bin/compile_mo
bin/transstats

cd ${CUR}
