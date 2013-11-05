#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

function usage {
    echo "Usage: $0 <hostname>"
    exit 1
}
[ -z "$1" ] && usage;

sudo ${TOPDIR}/scripts/bootstrap_server_ubuntu.sh ${1}
${TOPDIR}/scripts/bootstrap_checkout.sh
