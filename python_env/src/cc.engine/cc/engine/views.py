from webob import Response

from repoze.bfg.chameleon_zpt import render_template_to_response

from cc.engine import util


class FakeView(object):
    """
    Currently just used to satisfy the rtl stuff.  We need to get rid
    of this.
    """
    is_rtl =  False

    def get_ltr_rtl(self):
        return None

    def is_rtl_align(self):
        return None


def root_view(context, request):
    return Response("This is the root")


def licenses_view(context, request):
    template = util.get_zpt_template(
        'catalog_pages/licenses-index.pt')
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')

    fake_view = FakeView()

    return Response(
        template.pt_render(
            {'request': request,
             'engine_template': engine_template,
             'view': fake_view,
             'context': context}))


def specific_licenses_view(context, request):
    return Response(
        "this is the creative commons %s %s license" % (
            request.matchdict['license_id'],
            request.matchdict['license_version']))


def publicdomain_view(context, request):
    return Response("this is the public domain view")
