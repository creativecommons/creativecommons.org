from webob import Response

from repoze.bfg.chameleon_zpt import render_template_to_response

def root_view(context, request):
    return Response("This is the root")


def licenses_view(context, request):
    return Response("this is the licenses view")


def specific_licenses_view(context, request):
    return Response("this is the specific licenses view")


def publicdomain_view(context, request):
    return Response("this is the public domain view")
