#!/bin/bash
#
# This script:
# 1. Clones cc.engine and related respositories
#    - Checks out ARG1 branch (if specified)
# 2. Creates symlinks to support the semantic web
# 3. Creates Python Environment via pipenv
# 4. Generate ccengine.fcgi and copies config.ini into python_env
# 5. Compiles mo files and transstats
#    - Creates transstats.csv convenience symlink
set -o errexit
set -o errtrace
set -o nounset

trap '_es=${?};
    printf "${0}: line ${LINENO}: \"${BASH_COMMAND}\"";
    printf " exited with a status of ${_es}\n";
    exit ${_es}' ERR

BRANCH="${1:-master}"
DIR_SCRIPT="${0%/*}"
DIR_TOP="$(python -c \
    'import os.path, sys; print(os.path.normpath(sys.argv[1]))' \
    "${DIR_SCRIPT}/..")"
DIR_ABS_TOP="$(python -c \
    'import os.path, sys; print(os.path.realpath(sys.argv[1]))' "${DIR_TOP}")"

# Change directory to project root
pushd "${DIR_TOP}" >/dev/null

###############################################################################
# Clone/Update Repositories

pushd python_src >/dev/null
REPOS=(cc.engine cc.i18n cc.license cc.licenserdf rdfadict)
for _repo in "${REPOS[@]}"
do
    echo "${_repo}"
    if [[ ! -d "${_repo}" ]]
    then
        git clone "https://github.com/creativecommons/${_repo}.git"
        pushd "${_repo}">/dev/null
        for _script in ../post-clone.d/*
        do
            [[ -x "${_script}" ]] || continue
            "${_script}"
        done
        popd >/dev/null

    fi
    pushd "${_repo}">/dev/null
    git pull
    git checkout ${BRANCH}
    echo
    popd >/dev/null
done

# return from python_src
popd >/dev/null

###############################################################################
# Support the semantic web

echo 'Create symlinks to support the semantic web'
ln -fns python_src/cc.licenserdf \
   docroot/cc.licenserdf
ln -fns docroot/cc.licenserdf/cc/licenserdf/rdf \
   docroot/rdf
ln -fns ${DIR_TOP}/docroot/cc.licenserdf/cc/licenserdf/licenses \
   /docroot/license_rdf
echo

###############################################################################
# Create Python Environment

if [[ ! -d .venv ]]
then
    echo 'ERROR: missing .venv directory' 1>&2
    exit 1
fi
if [[ -f .venv/.project ]]
then
    echo '*skipping* Create Python Environment (already exists)'
else
    pipenv install
fi
echo

###############################################################################
# Generate ccengine.fcgi

if [[ -f python_env/bin/ccengine.fcgi ]]
then
    echo '*skipping* Generate ccengine.fcgi (already exists)'
else
    echo 'Generate ccengine.fcgi'
    sed -e "s|@env_dir@|${DIR_ABS_TOP}/python_env|" \
        < "python_src/bin/ccengine.fcgi.in" \
        > "python_env/bin/ccengine.fcgi"
    chmod 755 python_env/bin/ccengine.fcgi
fi
echo
if [[ -f python_env/config.ini ]]
then
    echo '*skipping* Copy config.ini into Python environment (already exists)'
else
    echo 'Copy config.ini into Python environment'
    cp python_src/config.ini python_env/config.ini
fi
echo

###############################################################################
# compile_mo & transstats are needed by cc.engine at runtime, run them now

echo 'Run compile_mo'
pipenv run compile_mo
echo
echo 'Run transstats'
pipenv run transstats
echo
echo 'Create transstats.csv convenience symlink'
ln -fns python_src/cc.i18n/cc/i18n/transstats.csv .
echo

###############################################################################
# Done!

# return from DIR_TOP
popd >/dev/null
