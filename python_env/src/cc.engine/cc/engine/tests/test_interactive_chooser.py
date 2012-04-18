from test_js import jstest




def test_current_state_initialization():
    jstest("http://localhost:6543/choose/", 
           """
           WAITFOR("window.$ !== undefined && window.CHOOSER !== undefined", 
                   function () {

              VIGIL("CHOOSER.STATE.current.field_format !== undefined",
                    "CHOOSER.STATE.current.field_format == ''",
                    "verify that CHOOSER.STATE.current is populated after page load.");
           });
           """,
           ignore=["TypeError: 'undefined' is not a function"])

