import sys
import urllib

import routes
from webob import Request, exc

from cc.engine import routing, staticdirect


class Error(Exception): pass
class ImproperlyConfigured(Error): pass


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
    def __init__(self, staticdirector, config):
        self.staticdirector = staticdirector
        self.config = config

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
                redirect = exc.HTTPMovedPermanently(location=new_path_info)
                return request.get_response(redirect)(environ, start_response)
            return exc.HTTPNotFound()(environ, start_response)
        controller = load_controller(route_match['controller'])
        request.start_response = start_response

        request.matchdict = route_match
        request.urlgen = routes.URLGenerator(routing.mapping, environ)
        request.staticdirect = self.staticdirector

        return controller(request)(environ, start_response)


def ccengine_app_factory(global_config, **kw):
    if kw.has_key('direct_remote_path'):
        staticdirector = staticdirect.RemoteStaticDirect(
            kw['direct_remote_path'].strip())
    elif kw.has_key('direct_remote_paths'):
        staticdirector = staticdirect.MultiRemoteStaticDirect(
            dict([line.strip().split(' ', 1)
                  for line in kw['direct_remote_paths'].strip().splitlines()]))
    else:
        raise ImproperlyConfigured(
            "One of direct_remote_path or direct_remote_paths must be provided")

    return CCEngineApp(staticdirector, config=kw)
