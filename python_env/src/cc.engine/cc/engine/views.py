import mimetypes

from webob import Response

from cc.engine import util


def root_view(request):
    return Response("/foo/what is at %s, /blah/bar is at %s" % (
            request.staticdirect('/foo/what/'),
            request.staticdirect('/blah/bar/')))
