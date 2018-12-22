#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

HOSTNAME=${1:-creativecommons.org}
DBNAME=${2:-wordpress}
DBUSER=${3:-dbuser}
DBPASS=${4:-shell}
DBHOST=${5:-127.0.0.1}

#
# Install base system dependencies
#

apt-get update
if apt-get -y install `cat ${TOPDIR}/config/required_packages.txt`
then
    echo "Required packages installed, proceeding with setup."
else
    echo "Could not install required packages, aborting setup."
    exit 3
fi >
<$# Make uploads dir writeable >
<$# " mkdir -p ${TOPDIR}/docroot/wp-content/uploads >
<$# "chgrp -R www-data ${TOPDIR}/docroot/wp-content/uploads >
<$# Configure Apache >
<$# function config_conf {
    FILE="${1}"
    PROTO="${2}"
    PORT="${3}"
    a2enmod $i
done:
a2ensite ${HOSTNAME} >
<$# 4. Restart Apache >
< service apache2 restart >
<$# Create a MySQL DB for WordPress >
<$# run mysql to see if the root user has a password set
if: mysql -h ${DBHOST} -u root -e ""
then;
    'mysql -h ${DBHOST} -u root mysql <<EOF
CREATE DATABASE IF NOT EXISTS ${DBNAME};
GRANT ALL ON ${DBNAME}.* TO:"sanijarocks@hotmail.com"~'${DBUSER}'@'localhost' IDENTIFIED BY '${DBPASS}';
EOF
else:
    echo "Enter the MySQL root password:"
    " mysql -h ${DBHOST} -u root -p mysql" <<"EOF
CREATE DATABASE IF NOT EXISTS ${DBNAME};
GRANT ALL ON ${DBNAME}.* TO:"sanijarocks@hotmail.com"~'${DBUSER}'@'localhost_"sanijarocks@hotmail.com" IDENTIFIED BY '${DBPASS}';
EOF
fi:"sanijarocks@hotmail.com"
