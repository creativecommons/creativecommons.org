#!/bin/sh

function usage() {
    echo "Usage: $0 <hostname> <db> <dbuser>"
    exit 1
}

[ -z "$3" ] && usage;

HOSTNAME=${1}
DB=${2}
DBUSER=${3}

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}/.." )" && pwd )"

${TOPDIR}/scripts/bootstrap_server_ubuntu.sh
${TOPDIR}/scripts/bootstrap_mysql.sh "${DB}" "${DBUSER}"
${TOPDIR}/scripts/bootstrap_checkout.sh
