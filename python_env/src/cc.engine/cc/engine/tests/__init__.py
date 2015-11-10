import subprocess
import time
TEST_SERVER_PROCESS = None
TEST_SERVER_PORT = "65432"


def setup_package():
    """Start the test server."""
    global TEST_SERVER_PROCESS

    print "Starting test server..."
    TEST_SERVER_PROCESS = subprocess.Popen(
        ("./bin/paster", "serve", "cc.engine.ini", "--server-name=test_server",
         "http_port="+TEST_SERVER_PORT),
        stdout=subprocess.PIPE,
        stderr=subprocess.PIPE)
    time.sleep(10)


def teardown_package():
    """Kill test server."""
    global TEST_SERVER_PROCESS

    print "Killing test server..."
    TEST_SERVER_PROCESS.kill()
