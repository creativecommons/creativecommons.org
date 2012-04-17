
#  Copyright 2012 Jonathan Palecek
#
#  Licensed under the Apache License, Version 2.0 (the "License");
#  you may not use this file except in compliance with the License.
#  You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
#  Unless required by applicable law or agreed to in writing, software
#  distributed under the License is distributed on an "AS IS" BASIS,
#  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#  See the License for the specific language governing permissions and
#  limitations under the License.


#  See https://github.com/Lunpa/phantom_nose for more details.




import tempfile
import subprocess


class JavascriptError (Exception):
    pass

class SyntaxError (JavascriptError):
    pass

class ReferenceError (JavascriptError):
    pass

JSTESTLOADER = None
def jstest_loader():
    """
    Creates a temporary file containing javascript for running unit tests.
    Returns the path to the script.
    """
    global JSTESTLOADER
    if not JSTESTLOADER:
        fileno, path = tempfile.mkstemp();
        loader = open(path, "r+w");
        loader.write("""
var page = require('webpage').create();
if (phantom.args.length !== 2) {
    console.error("Wrong number of arguments passed.");
    console.info("args passed: " + phantom.args);
    phantom.exit();
}
var page_url = phantom.args[0];
var test_path = phantom.args[1];

var page_setup = function () {
    window.JSTEST = {
        "active" : true,
        "waiting" : [],
        "is_waiting" : function () {
            return window.JSTEST.waiting.length > 0;
        }
    };
    window.ASSERT = function (statement, hint) {
        if (!statement) {
            console.error("ASSERTION FAILED: " + hint);
        }
    };
    window.WAITFOR = function (check, callback) {
        window.JSTEST.waiting.push(true);
        var start_time = new Date().getTime();
        var interval = window.setInterval(function () {
            if (typeof(check) === "string" ? eval(check) : check()) {
                window.clearInterval(interval);
                window.JSTEST.waiting.pop();
                if (window.JSTEST.waiting.length === 0) {
                    window.JSTEST.active = false;
                }
                try {
                    callback();
                }
                catch (err) {
                    console.error("ERROR: " + err);
                }
            }
            else {
                var benchmark = (new Date().getTime()) - start_time;
                if (benchmark > 10000) {
                    window.clearInterval(interval);
                    console.error("ERROR: Timeout while waiting for " + check);
                    window.JSTEST.waiting.pop();
                    if (window.JSTEST.waiting.length === 0) {
                        window.JSTEST.active = false;
                    }
                }
            }
        });
    };
};

var page_payload = function () {
    var error = false;
    try {
        UNIT_TEST();
    }
    catch (err) {
        error = true;
        console.error("ERROR: " + err);
        window.JSTEST.waiting = [];
    }
    window.JSTEST.active = window.JSTEST.is_waiting();
};

var is_active = function () {
    return page.evaluate(function(){
        return window.JSTEST.is_waiting() || window.JSTEST.active;
    });
};

page.onConsoleMessage = function (msg) {
    console.log("ERROR: "+msg);
};

page.onError = function (msg, trace) {
    console.log("ERROR: "+msg);
};

page.onLoadFinished = function (status) {
    try {
        if (status !== "success") {
            throw("Failed to open url.");
        }
        else {
            page.evaluate(page_setup);
            if (!page.injectJs(test_path)) {
                throw("Failed to inject test code.");
            }
            else {
                page.evaluate(page_payload);
                setInterval(function () {
                    if (!is_active()) {
                        phantom.exit();
                    }
                }, 500);
            }
        }
    }
    catch (msg) {
        console.log("ERROR: " + msg);
        phantom.exit();
    }
};

page.open(page_url);
""");
        loader.close()
        JSTESTLOADER = path
    return JSTESTLOADER


    

def jstest(url, test, ignore=None):
    """Runs a unit test written in javascript.
    Warning, use 'ASSERT(test, hint)' instead of console.assert.""";
    
    fileno, path = tempfile.mkstemp()
    jstemp = open(path, "r+w")
    jstemp.write("var UNIT_TEST = function () {");
    jstemp.write(test)
    jstemp.write("};");
    jstemp.close()
    proc = subprocess.Popen(["phantomjs", "--disk-cache=yes", 
                             jstest_loader(), url, path],
                            stdout=subprocess.PIPE)
    out = proc.communicate()[0].strip()
    if out.count("ERROR")>0:
        for line in out.split("\n"):
            line = line.strip()
            if line.count("ASSERTION FAILED"):
                raise AssertionError(line)
            elif line.count("SyntaxError"):
                raise SyntaxError("\n"+out)
            elif line.count("ReferenceError"):
                raise ReferenceError("\n"+out)
            elif line.count("ERROR") or line.count("Error"):
                # just show the entire output to be more clear of what failed
                ignored = False
                if ignore != None:
                    if type(ignore) in [list, tuple]:
                        for check in ignore:
                            if line.count(check) > 0:
                                ignored = True
                                break
                    elif line.count(ignore) > 0:
                        ignored = True                        
                if not ignored:
                    raise JavascriptError("\n"+out)




def test_jstest():
    """Test the test code =)"""

    def check_test(test, expected=True, url="about:blank"):
        """Argument 'expected' is true if the test should pass, and
        is False if the test should fail."""
        passed = True;
        try:
            jstest(url, test)
        except AssertionError:
            passed = False
        assert passed == expected
        
    syntax_fail = False
    try:
        check_test("(((((some syntax error(((((", True)
    except SyntaxError:
        syntax_fail = True
    assert syntax_fail
    try:
        check_test("ASSERT(true, 'assert true');", None, "blahlblahbsurl")
    except JavascriptError as err:
        assert str(err).count("ERROR: Failed to open url.") == 1

    check_test("ASSERT(true, 'assert true');", True)
    check_test("ASSERT(false, 'assert false');", False)

    check_test("""
        window.READY = false;
        window.setTimeout(function () {
            window.READY = true;
        }, 200);
        WAITFOR("window.READY", function () {
            ASSERT(true, 'assert true');
        });
        """, True)

    try:
        check_test("""
            WAITFOR("false", function () {
                ASSERT(true, 'assert true');
            });
            """, None)
    except JavascriptError as err:
        assert str(err).count("Timeout while waiting for false") == 1
    
    # WAITFOR should be nestable
    check_test("""
        window.READY_A = false;
        window.READY_B = false;
        window.setTimeout(function () {
            window.READY_A = true;
        }, 200);
        window.setTimeout(function () {
            window.READY_B = true;
        }, 1000);
        WAITFOR("window.READY_A", function () {
            WAITFOR("window.READY_B", function () {
                ASSERT(false, 'assert false');
            });
        });
        """, False)

    # WAITFOR should work concurrently (longer wait asserts false)
    check_test("""
        window.READY_A = false;
        window.READY_B = false;
        window.setTimeout(function () {
            window.READY_A = true;
        }, 200);
        window.setTimeout(function () {
            window.READY_B = true;
        }, 1000);
        WAITFOR("window.READY_A", function () {
        });
        WAITFOR("window.READY_B", function () {
            ASSERT(false, 'assert false');
        });
        """, False)

    # WAITFOR should work concurrently (shorter wait asserts false)
    check_test("""
        window.READY_A = false;
        window.READY_B = false;
        window.setTimeout(function () {
            window.READY_A = true;
        }, 200);
        window.setTimeout(function () {
            window.READY_B = true;
        }, 1000);
        WAITFOR("window.READY_A", function () {
            ASSERT(false, 'assert false');
        });
        WAITFOR("window.READY_B", function () {
        });
        """, False)

