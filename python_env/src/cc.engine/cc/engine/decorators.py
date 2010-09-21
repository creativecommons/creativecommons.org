from webob.exc import HTTPNotFound, HTTPMethodNotAllowed

from cc.license import by_code, CCLicenseError

def _make_safe(decorator, original):
    """
    Copy the function data from the old function to the decorator.
    """
    decorator.__name__ = original.__name__
    decorator.__dict__ = original.__dict__
    decorator.__doc__ = original.__doc__
    return decorator


def get_license(controller):
    def new_controller_func(request, *args, **kwargs):
        try:
            license = by_code(
                request.matchdict['code'],
                jurisdiction=request.matchdict.get('jurisdiction'),
                version=request.matchdict.get('version'))
        except CCLicenseError:
            return HTTPNotFound()

        return controller(request, license=license, *args, **kwargs)

    return _make_safe(new_controller_func, controller)


class RestrictHttpMethods(object):
    def __init__(self, *allowed_methods):
        self.allowed_methods = allowed_methods

    def __call__(controller):
        def new_controller_func(request, *args, **kwargs):
            if request.method not in self.allowed_methods
                return HTTPMethodNotAllowed()

            return controller(request, license=license, *args, **kwargs)

        return _make_safe(new_controller_func, controller)
