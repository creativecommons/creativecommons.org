#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

HOSTNAME=${1:-creativecommons.org}
DBNAME=${2:-wordpress}
DBUSER=${3:-dbuser}
DBPASS=${4:-}

pushd ${TOPDIR}

#
# composer
#

wget -O - https://getcomposer.org/installer | php

#
# WordPress
#

php ${TOPDIR}/composer.phar install

# Make sure wp-content hierarchy is correct

mkdir -p ${TOPDIR}/docroot/wp-content/themes
mkdir -p ${TOPDIR}/docroot/wp-content/uploads
chgrp -R www-data ${TOPDIR}/docroot/wp-content/uploads

#
# Theme
#

if [ ! -d "${TOPDIR}/cc-wp-theme" ]
then
    git clone https://github.com/creativecommons/cc-wp-theme.git \
        "${TOPDIR}/cc-wp-theme"
else
    pushd "${TOPDIR}/cc-wp-theme"
    git pull
    popd
fi

if [ ! -d "${TOPDIR}/docroot/wp-content/themes/creativecommons.org" ]
then
    ln -s "${TOPDIR}/cc-wp-theme/creativecommons.org" \
       "docroot/wp-content/themes/creativecommons.org"
    ln -s "${TOPDIR}/cc-wp-theme/creativecommons.org" \
       "docroot/wp-content/themes/twentyfourteen"
fi

#
# wp-config-local.php
#

sed -e "s|@dbname@|${DBNAME}|;s|@dbuser@|${DBUSER}|;s|@dbpass@|${DBPASS}|" \
    < "docroot/wp-config-local.php.in" \
    > "docroot/wp-config-local.php"

popd # to original
