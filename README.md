# Installing cc.engine

scripts/bootstrap_python.sh

# Testing cc.engine

## Command Line

cd python_env
source bin/activate
REQUEST_URI=/licenses/by-sa/4.0/ REQUEST_METHOD=GET \
SERVER_NAME=creativecommons.org SERVER_PORT=80 SERVER_PROTOCOL=http \
python3 bin/ccengine.fcgi

## Local Server

cd python_env
source bin/activate
pip3 install gevent
python3 bin/ccengine-local.py
