import subprocess
from test_js import jstest, JavascriptError

PASTER = subprocess.Popen(["paster", "serve", "cc.engine.ini"])


def test_something():
    jstest("http://localhost:6543/", "ASSERT(false, 'test should fail');")
