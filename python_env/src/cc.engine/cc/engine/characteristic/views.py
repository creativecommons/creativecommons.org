from cc.engine import util
from webob import Response


def characteristic_view(request):
    """
    Return one of the characteristic description pages.
    """
    target_lang = util.get_target_lang_from_request(request)

    template_name = 'characteristic/%s.html' % (
        request.matchdict['characteristic'])

    context = {'request': request}
    context.update(util.rtl_context_stuff(target_lang))

    return Response(
        util.render_template(
            request, target_lang,
            template_name, context))
