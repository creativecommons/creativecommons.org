#!/bin/bash

CUR=`pwd`
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd ${DIR}

git submodule init
git submodule update

cd python_env

sed -e "s|@env_dir@|${DIR}/python_env|" < bin/ccengine.fcgi.in > bin/ccengine.fcgi
chmod 755 bin/ccengine.fcgi

virtualenv .
source bin/activate

for i in 'setuptools>=0.7' 'zope.interface>=3.8.0' Paste PasteDeploy PasteScript RDF
do
    pip install $i
done

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
