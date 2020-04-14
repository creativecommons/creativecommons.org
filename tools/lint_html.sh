#!/bin/bash
set -o errexit
set -o errtrace
set -o nounset

trap '_es=${?};
    printf "${0}: line ${LINENO}: \"${BASH_COMMAND}\"";
    printf " exited with a status of ${_es}\n";
    exit ${_es}' ERR

PROG="${0##*/}"
USAGE="
Usage:  ${PROG} HTML_FILES
        ${PROG}


Lints HTML files using tidy and displays warnings in yellow and errors in red.


Arguments:

    HTML_FILES  Path or glob indicating which files to lint.


Example:

    Assuming the current directory is docroot/legalcode/

    ../../tools/${PROG} *_en*
"
# Colors
RED=$(printf '\e[91m')
RST=$(printf '\e[0m')
WHT=$(printf '\e[97m')
YLW=$(printf '\e[93m')

#### FUNCTIONS ################################################################


help_print() {
    # Print help/usage, then exit (incorrect usage should exit 2)
    echo "${USAGE}" 1>&2
    exit 2
}


#### MAIN #####################################################################


(( ${#} == 0 )) && help_print

for _html_file in ${@}
do
    [[ -f "${_html_file}" ]] || continue
    [[ "${_html_file}" =~ [.]html ]] || continue
    echo "${WHT}${_html_file}${RST}"
    tidy -errors -quiet -utf8 "${_html_file}" 2>&1 | sed \
        -e"s/\\(^.*\\) - Error\\(:.*\$\\)/${RED}\\1\\2${RST}/" \
        -e"s/\\(^.*\\) - Warning\\(:.*\$\\)/${YLW}\\1\\2${RST}/"
    echo
done
