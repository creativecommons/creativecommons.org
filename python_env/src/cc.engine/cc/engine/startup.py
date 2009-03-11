import os
import sys
import code
import zdaemon.zdctl
import zope.app.wsgi
import zope.app.debug

def application_factory(global_conf, conf='zope.conf'):
    zope_conf = os.path.join(global_conf['here'], conf)
    return zope.app.wsgi.getWSGIApplication(zope_conf)

def interactive_debug_prompt(zope_conf='zope.conf'):
    db = zope.app.wsgi.config(zope_conf)
    debugger = zope.app.debug.Debugger.fromDatabase(db)
    # Invoke an interactive interpreter shell
    banner = ("Welcome to the interactive debug prompt.\n"
              "The 'root' variable contains the ZODB root folder.\n"
              "The 'app' variable contains the Debugger, 'app.publish(path)' "
              "simulates a request.")
    code.interact(banner=banner, local={'debugger': debugger,
                                        'app':      debugger,
                                        'root':     debugger.root()})

class ControllerCommands(zdaemon.zdctl.ZDCmd):

    def do_debug(self, rest):
        interactive_debug_prompt()

    def help_debug(self):
        print "debug -- Initialize the application, providing a debugger"
        print "         object at an interactive Python prompt."

def zdaemon_controller(zdaemon_conf='zdaemon.conf'):
    args = ['-C', zdaemon_conf] + sys.argv[1:]
    zdaemon.zdctl.main(args, options=None, cmdclass=ControllerCommands)
