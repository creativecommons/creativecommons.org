"""Decorators to isolate optimization code."""

class cached(object):
    """Decorator that caches a property value after the first call."""

    def __init__(self, f):
        self.f = f

    def __call__(self, *args):

        try:
            return self.cached
        except AttributeError:
            self.cached = self.f(*args)
            return self.cached

    def __repr__(self):
        """Return the decorated function's docstring."""

        return self.func.__doc__

def memoize(f):
    # XXX we don't do anything now...
    return f
