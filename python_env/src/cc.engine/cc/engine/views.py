from webob import Response


def root_view(request):
    return Response("This is the root")
