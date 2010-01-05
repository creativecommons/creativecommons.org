import urlparse


class StaticDirect(object):
    def __init__(self):
        self.cache = {}

    def __call__(self, request, filepath):
        if self.cache.has_key(filepath):
            return self.cache[filepath]

        static_direction = self.cache[filepath] = self.get(request, filepath)
        return static_direction
        

    def get(self, request, filepath):
        # should be implemented by the individual staticdirector
        pass


class LocalStaticDirect(StaticDirect):
    """
    Static serving handled by cc.engine.  Generally not a good idea,
    except for development!
    """
    def get(self, request, filepath):
        return request.urlgen('staticserve', filename=filepath)


class RemoteStaticDirect(StaticDirect):
    def __init__(self, remotepath):
        self.remotepath = remotepath

    def get(self, request, filepath):
        return urlparse.urljoin(self.remotepath, filepath)


class MultiRemoteStaticDirect(StaticDirect):
    """
    """
    def __init__(self, remotepaths):
        self.remotepaths = remotepaths

    def get(self, request, filepath):
        section, rest = filepath.strip('/').split('/', 1)

        return urlparse.urljoin(
            self.remotepaths[section], rest)
