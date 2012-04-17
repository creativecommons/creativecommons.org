#from paste.deploy import loadapp
#from flup.server.fcgi import WSGIServer
from test_js import jstest


#def launch_test_server(foo):
#    import os, time
#    WSGIServer(loadapp('config:' + os.getcwd()+"/cc.engine.ini")).run()
#    time.sleep(20)
#    foo()


def test_current_state_initialization():
    #jstest("http://localhost:6543/", "ASSERT(false, 'test should fail');")
    jstest("http://localhost:6543/choose/", 
           """
           WAITFOR("window.$ !== undefined && window.CHOOSER !== undefined", 
                   function () {

              WAITFOR("CHOOSER.STATE.current.field_format !== undefined",
                      function () {

                  ASSERT(CHOOSER.STATE.current.field_format == "",
                         'arbitrary assertion');
              });
           });
           """,
           ignore=["TypeError: 'undefined' is not a function"])

