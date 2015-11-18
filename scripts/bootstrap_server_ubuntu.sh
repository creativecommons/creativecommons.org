#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

HOSTNAME=${1:-creativecommons.org}
DBNAME=${2:-wordpress}
DBUSER=${3:-dbuser}
DBPASS=${4:-}
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
    exit 1
fi

#
# Configure Apache
#

function config_conf {
    FILE="${1}"
    PROTO="${2}"
    PORT="${3}"
    perl -p -i -e "s/\\$\{port\}/${PORT}/g" "${FILE}"
    perl -p -i -e "s/\\$\{host\}/${HOSTNAME}/g" "${FILE}"
    perl -p -i -e "s/\\$\{proto\}/${PROTO}/g" "${FILE}"
    perl -p -i -e "s|\\$\{dir\}|${TOPDIR}|g" "${FILE}"
    perl -p -i -e "s|\\$\{logdir\}|/var/log/apache2/${HOSTNAME}|g" "${FILE}"
}

HTTPSCONF="/etc/apache2/sites-available/${HOSTNAME}.conf"
cp ${TOPDIR}/config/apache.conf "${HTTPSCONF}"
config_conf "${HTTPSCONF}" https 443

# 2. Create logging directory

mkdir -p /var/log/apache2/${HOSTNAME}
chown root.adm /var/log/apache2/${HOSTNAME}
chmod 750 /var/log/apache2/${HOSTNAME}

# 3. Enable mods and site

for i in macro php5 rewrite ssl fcgid
do
    a2enmod $i
done

a2ensite ${HOSTNAME}

# 4. Restart Apache

service apache2 restart

#
# Create a MySQL DB for WordPress
#

# run mysql to see if the root user has a password set
if mysql -h ${DBHOST} -u root -e ""
then
    mysql -h ${DBHOST} -u root mysql <<EOF
CREATE DATABASE IF NOT EXISTS ${DBNAME};
GRANT ALL ON ${DBNAME}.* TO '${DBUSER}'@'localhost' IDENTIFIED BY '${DBPASS}';
EOF
else
    echo "Enter the MySQL root password:"
    mysql -h ${DBHOST} -u root -p mysql <<EOF
CREATE DATABASE IF NOT EXISTS ${DBNAME};
GRANT ALL ON ${DBNAME}.* TO '${DBUSER}'@'localhost' IDENTIFIED BY '${DBPASS}';
EOF
fi
