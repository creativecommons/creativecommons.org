#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

function usage {
    echo "Usage: $0 <hostname> [db] [db user] [db user password]"
    exit 1
}
[ -z "$1" ] && usage;

sudo ${TOPDIR}/scripts/bootstrap_server_ubuntu.sh $*
${TOPDIR}/scripts/bootstrap_checkout.sh $*
