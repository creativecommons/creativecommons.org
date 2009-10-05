from webob import Response

from repoze.bfg.chameleon_zpt import render_template_to_response

from cc.engine import util


def root_view(context, request):
    return Response("This is the root")


def licenses_view(context, request):
    template = util.get_zpt_template('catalog_pages/licenses-index.pt')
    return Response(
        template.pt_render(
            {'response': response}))


def specific_licenses_view(context, request):
    return Response("this is the specific licenses view")


def publicdomain_view(context, request):
    return Response("this is the public domain view")
