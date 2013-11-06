#!/bin/bash

CUR=`pwd`
TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

HOSTNAME=${1:-creativecommons.org}
DBNAME=${2:-wordpress}
DBUSER=${3:-dbuser}
DBPASS=${4:-}

cd ${TOPDIR}

#
# Git submodules
#

git submodule init
git submodule update

cd python_env

#
# Set up Python env
#

virtualenv .
source bin/activate

for i in 'setuptools>=0.7' 'zope.interface>=3.8.0' Paste PasteDeploy PasteScript RDF cssselect transifex-client
do
    pip install $i
done

# On Ubuntu, virtualenv setups don't "see" dist-packages, which is
# where Ubuntu-packaged modules go. This works around that problem:

echo "/usr/lib/python2.7/dist-packages/" > lib/python2.7/site-packages/dist-packages.pth


#
# Set up each Python module
#

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

#
# compile_mo & transstats are needed by cc.engine at runtime, run them now
#

bin/compile_mo
bin/transstats

cd .. # topdir

#
# Generate ccengine.fcgi & wp-config-local.php
#

subst="s|@env_dir@|${TOPDIR}/python_env|;s|@dbname@|${DBNAME}|;s|@dbuser@|${DBUSER}|;s|@dbpass@|${DBPASS}|"
subst_files="python_env/bin/ccengine.fcgi docroot/wp-config-local.php"

for i in ${subst_files}
do
    sed -e "${subst}" < "$i.in" > "$i"
done

chmod 755 python_env/bin/ccengine.fcgi

cd ${CUR}
