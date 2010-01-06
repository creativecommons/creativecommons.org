import mimetypes

from webob import Response

from cc.engine import util


def root_view(request):
    return Response("This is the root")
