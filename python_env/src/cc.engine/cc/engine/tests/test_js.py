
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
var ASSERT = function (statement, hint) {
     if (!statement) {
         throw("ASSERTION FAILED: " + hint);
     }
};
(function() {
    var page = require('webpage').create();
    if (phantom.args.length !== 2) {
        console.error("Wrong number of arguments passed.");
        console.info("args passed: " + phantom.args);
        phantom.exit();
    }
    var page_url = phantom.args[0];
    var test_path = phantom.args[1];

    page.open(page_url, function (status) {
        try {
            if (status === "fail") {
                throw("Failed to open url.");
            }
            else {
                if (!phantom.injectJs(test_path)) {
                    throw("Failed to inject test code.");
                }
                else {
                    UNIT_TEST();
                }
            }
        }
        catch (msg) {
            console.log("ERROR: " + msg);
        }
        phantom.exit();
    });
})();
""");
        loader.close()
        JSTESTLOADER = path
    return JSTESTLOADER


    

def jstest(url, test):
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
                            stdout=subprocess.PIPE);
    out = proc.communicate()[0].strip()
    if out.count("ERROR")>0:
        for line in out.split("\n"):
            line = line.strip()
            if line.count("ASSERTION FAILED"):
                raise AssertionError(line)
            elif line.count("ERROR") or line.count("Error"):
                # just show the entire output to be more clear of what failed
                raise JavascriptError("\n"+out)


def test_jstest():
    """Test the test code =)"""

    def check_test(test, expected=True, url="http://google.com"):
        """Argument 'expected' is true if the test should pass, and
        is False if the test should fail."""
        passed = True;
        try:
            jstest(url, test)
        except AssertionError:
            passed = False
        assert passed == expected
        
    check_test("ASSERT(true, 'assert true');", True)
    check_test("ASSERT(false, 'assert false');", False)
    try:
        check_test("(((((some syntax error(((((", True)
    except JavascriptError as err:
        assert str(err).count("undefined:1 SyntaxError: Parse error") == 1
    try:
        check_test("ASSERT(true, 'assert true');", None, "blahlblahbsurl")
    except JavascriptError as err:
        assert str(err).count("ERROR: Failed to open url.") == 1

