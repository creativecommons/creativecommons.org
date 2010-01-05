import sys
import urllib

import routes
from webob import Request, exc

from cc.engine import routing, static


def load_controller(string):
    module_name, func_name = string.split(':', 1)
    __import__(module_name)
    module = sys.modules[module_name]
    func = getattr(module, func_name)
    return func


class CCEngineApp(object):
    """
    Really basic wsgi app using routes and WebOb.
    """
    def __init__(self):
        self.staticdirect = static.LocalStaticDirect()

    def __call__(self, environ, start_response):
        request = Request(environ)
        path_info = request.path_info
        route_match = routing.mapping.match(path_info)
        if route_match is None:
            if not path_info.endswith('/') \
                    and request.method == 'GET' \
                    and routing.mapping.match(path_info + '/'):
                new_path_info = path_info + '/'
                if request.GET:
                    new_path_info = '%s?%s' % (
                        new_path_info, urllib.urlencode(request.GET))
                redirect = exc.HTTPTemporaryRedirect(location=new_path_info)
                return request.get_response(redirect)(environ, start_response)
            return exc.HTTPNotFound()(environ, start_response)
        controller = load_controller(route_match['controller'])
        request.start_response = start_response
        request.matchdict = route_match
        request.urlgen = routes.URLGenerator(routing.mapping, environ)
        request.staticdirect = lambda filepath: self.staticdirect(
            request, filepath)
        return controller(request)(environ, start_response)


def ccengine_app_factory(global_config, **kw):
    return CCEngineApp()
