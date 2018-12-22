#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"
SCRIPTSDIR="${TOPDIR}/scripts"

HOSTNAME="${1:-creativecommons.org}"
DBNAME="${2:-wordpress}"
DBUSER="${3:-dbuser}"
DBPASS="${4:-}"

# Set up the Python environment

"${SCRIPTSDIR}/bootstrap_python.sh"

# Set up WordPress (this is now replaced with new-creativecommons.org"

"${SCRIPTSDIR}/bootstrap_wordpress.sh" \
    "${HOSTNAME}" "${DBNAME}" "${DBUSER}" "${DBPASS}"
