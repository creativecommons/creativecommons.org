import mimetypes

from webob import Response

from cc.engine import util


def root_view(request):
    return Response("/foo/what is at %s, /blah/bar is at %s" % (
            request.staticdirect('/foo/what/'),
            request.staticdirect('/blah/bar/')))


def staticserve_view(request):
    filename = request.matchdict['filename']
    mimetype = mimetypes.guess_type(filename)[0]

    response = Response(content_type=mimetype)
    content_file = file(
        util.safer_resource_filename(
            'cc.engine', 'resources/' + filename.lstrip('/')))

    response.body_file.write(content_file.read())
    return response
