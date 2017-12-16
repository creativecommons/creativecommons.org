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

mkdir -p ${TOPDIR}/docroot/wp-content/uploads
chgrp -R www-data ${TOPDIR}/docroot/wp-content/uploads

#
# wp-config-local.php
#

sed -e "s|@dbname@|${DBNAME}|;s|@dbuser@|${DBUSER}|;s|@dbpass@|${DBPASS}|" \
    < "docroot/wp-config-local.php.in" \
    > "docroot/wp-config-local.php"

popd # to original
