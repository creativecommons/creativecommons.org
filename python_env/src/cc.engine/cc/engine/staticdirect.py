import pkg_resources
import urlparse

import static


class StaticDirect(object):
    def __init__(self):
        self.cache = {}

    def __call__(self, filepath):
        if self.cache.has_key(filepath):
            return self.cache[filepath]

        static_direction = self.cache[filepath] = self.get(filepath)
        return static_direction
        

    def get(self, filepath):
        # should be implemented by the individual staticdirector
        pass


class RemoteStaticDirect(StaticDirect):
    def __init__(self, remotepath):
        StaticDirect.__init__(self)
        self.remotepath = remotepath.rstrip('/')

    def get(self, filepath):
        return '%s/%s' % (
            self.remotepath, filepath.lstrip('/'))


class MultiRemoteStaticDirect(StaticDirect):
    """
    For whene separate sections of the static data is served under
    separate urls.
    """
    def __init__(self, remotepaths):
        StaticDirect.__init__(self)
        self.remotepaths = remotepaths

    def get(self, filepath):
        section, rest = filepath.strip('/').split('/', 1)

        return '%s/%s' % (
            self.remotepaths[section].rstrip('/'),
            rest.lstrip('/'))


def static_app_factory(global_config, **kw):
    resource, path = kw['resource_path'].split(':', 1)
    return static.Cling(pkg_resources.resource_filename(resource, path))
