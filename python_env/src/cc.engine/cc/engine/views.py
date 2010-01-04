import mimetypes

from webob import Response

from cc.engine import util


def root_view(request):
    return Response("This is the root")


def staticserve_view(request):
    filename = request.matchdict['filename']
    mimetype = mimetypes.guess_type(filename)[0]

    response = Response(content_type=mimetype)
    content_file = file(
        util.safer_resource_filename(
            'cc.engine.resources', filename))

    response.body_file.write(content_file.read())
    return response
