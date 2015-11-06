#!/bin/bash

CUR=`pwd`
TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

HOSTNAME=${1:-creativecommons.org}
DBNAME=${2:-wordpress}
DBUSER=${3:-dbuser}
DBPASS=${4:-}

cd ${TOPDIR}

#
# Theme
#

if [ -d "docroot/wp-content/themes/creativecommons.org" ]
then
    pushd "docroot/wp-content/themes/creativecommons.org"
    git pull
    popd
else
    git clone https://github.com/creativecommons/cc-wp-theme.git
    ln -s "${TOPDIR}/creativecommons.org" \
       "docroot/wp-content/themes/creativecommons.org"
fi

cd python_env

#
# Set up Python env
#

virtualenv .
source bin/activate

# No RDF in pip (it's rdfutils)

for i in 'setuptools>=0.7' 'zope.interface>=3.8.0' Paste PasteDeploy \
                           PasteScript rdfutils  cssselect transifex-client
do
    pip install $i
done

# On Ubuntu, virtualenv setups don't "see" dist-packages, which is
# where Ubuntu-packaged modules go. This works around that problem:

echo "/usr/lib/python2.7/dist-packages/" > lib/python2.7/site-packages/dist-packages.pth

#
# Check out and set up each Python module
#

pushd src

REPOS=(i18n license.rdf cc.license cc.engine)
for i in "${REPOS[@]}"
do
    if [ -d "${i}" ]
    then
        pushd "${i}"
        git pull
        popd
    else
        git clone "https://github.com/creativecommons/${i}.git"
    fi
      done

for i in "${REPOS[@]}"
do
    pushd "${i}"
    python bootstrap.py -v 2.1.1
    bin/buildout
    python setup.py develop
    popd
done

popd # python_env

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
