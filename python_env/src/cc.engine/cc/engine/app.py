import sys
import urllib

from webob import Request, exc

from cc.engine import routing


def load_controller(string):
    module_name, func_name = string.split(':', 1)
    __import__(module_name)
    module = sys.modules[module_name]
    func = getattr(module, func_name)
    return func


def ccengine_app(environ, start_response):
    """
    Really basic wsgi app using routes and WebOb.
    """
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
    return controller(request)(environ, start_response)


def ccengine_app_factory(global_config, **kw):
    return ccengine_app
