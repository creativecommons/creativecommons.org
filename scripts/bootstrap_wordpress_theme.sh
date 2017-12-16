#!/bin/bash

TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )/.." && pwd )"

pushd ${TOPDIR}

#
# WordPress theme
#

# Make sure wp-content hierarchy is correct

mkdir -p ${TOPDIR}/docroot/wp-content/themes

if [ ! -d "${TOPDIR}/new-www-theme" ]
then
    git clone https://github.com/creativecommons/new-www-theme.git \
        "${TOPDIR}/new-www-theme"
else
    pushd "${TOPDIR}/new-www-theme"
    git pull
    popd
fi

if [ ! -d "${TOPDIR}/docroot/wp-content/themes/cc" ]
then
    ln -s "${TOPDIR}/new-www-theme/cc" \
       "docroot/wp-content/themes/cc"
fi

popd # to original
