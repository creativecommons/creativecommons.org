#!/bin/bash
set -o nounset
set -o pipefail

tidy -errors -quiet -utf8 --show-filename yes --show-warnings no \
    docroot/legalcode/*.html \
    2>&1 | sed -e'/^Tidy: /d'
case ${?} in
    2) exit 2;; # errors
    1) exit 0;; # warning (ignored)
    0) exit 0;; # success
esac
