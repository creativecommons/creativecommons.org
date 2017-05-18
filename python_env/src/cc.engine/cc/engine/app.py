import re
import sys
import urllib

import routes
from webob import Request, exc

from cc.engine import routing, staticdirect, util


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

    def form_value_ok(self, form, key, regex):
        """True if the value is absent, or is present and matches the regex"""
        result = True
        if form.has_key(key) and form[key] != '':
            result = re.match(regex, form[key])
        return result

    # FIXME / TODO .
    # This should be broken into several methods for different views.
    # But is here at the moment to ensure this approach works before refactoring.
    def is_form_ok(self, form):
        """Check for e.g. SQL injection attempts in form values."""
        result = self.form_value_ok(form, 'license_code',
                                    r'[a-z-]+') and \
                 self.form_value_ok(form, 'version',
                                    r'([0-9.]+|version)') and \
                 self.form_value_ok(form, 'jurisdiction',
                                    r'([a-zA-Z_-]+)')
        return result

    def __call__(self, environ, start_response):
        request = Request(environ)

        # Get the path info, which will break if we've been fed an invalid
        # utf-8 uri. So catch that eventuality and 404 on it.
        try:
            path_info = request.path_info
        except UnicodeDecodeError, e:
            response = util.generate_404_response(request, routing, environ,
                                                  self.staticdirector)
            return response(environ, start_response)

        # If we can't get the form because of a Unicode error it contains badly
        # encoded data that will blow things up later so bail now.
        try:
            form = request.GET or request.POST
        except UnicodeDecodeError, e:
            # Someone fed us some un-encoded or badly encoded values in the form
            response = exc.HTTPBadRequest('Character encoding error in query.')
            return response(environ, start_response)

        # Avoid invalid lang specs not of the form aa aa-aa aa-AA aa_aa aa_AA .
        # Redirect to untranslated version if user specified an invalid lang.
        # This should be moved into views.
        if not self.form_value_ok(form, 'lang', r'^[a-z]{2}([-_][a-zA-Z]{2})?$'):
            del form['lang']
            response = exc.HTTPFound(location=request.path_info)
            return response(environ, start_response)

        # Show error if any form arguments seem bad (e.g. they are attempts at
        # SQL injection [which breaks SPARQL])
        # This should be moved into views.
        if not self.is_form_ok(form):
            response = exc.HTTPBadRequest('One or more query values were bad.')
            return response(environ, start_response)

        route_match = routing.mapping.match(path_info)

        if route_match is None:
            # If there's an equivalent URL that ends with /, redirect
            # to that.
            if not path_info.endswith('/') \
                    and request.method == 'GET' \
                    and routing.mapping.match(path_info + '/'):
                new_path_info = path_info + '/'
                if request.GET:
                    new_path_info = '%s?%s' % (
                        new_path_info, urllib.urlencode(request.GET))
                redirect = exc.HTTPFound(location=new_path_info)
                return request.get_response(redirect)(environ, start_response)
            # Return a 404
            response = util.generate_404_response(
                request, routing, environ, self.staticdirector)
            return response(environ, start_response)

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
