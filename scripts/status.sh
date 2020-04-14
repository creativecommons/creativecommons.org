#!/bin/bash
set -o errexit
set -o errtrace
set -o nounset

trap '_es=${?};
    printf "${0}: line ${LINENO}: \"${BASH_COMMAND}\"";
    printf " exited with a status of ${_es}\n";
    exit ${_es}' ERR 

DIR_SCRIPT="${0%/*}"
DIR_TOP="$(python -c \
    'import os.path, sys; print(os.path.normpath(sys.argv[1]))' \
    "${DIR_SCRIPT}/..")"

REPOS='.
python_src/cc.engine
python_src/cc.i18n
python_src/cc.license
python_src/cc.licenserdf
python_src/rdfadict'

for _r in ${REPOS}
do
    _r="${DIR_TOP}/${_r}"
    [[ -d ${_r} ]] || continue
    pushd ${_r} >/dev/null
    printf "\e[1m\e[7m %-40s\e[0m\n" ${PWD##*/}
    git status
    echo
    echo
    popd >/dev/null
done
