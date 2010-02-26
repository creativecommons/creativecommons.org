from cc.engine import util
from webob import Response


def characteristic_view(request):
    """
    Return one of the characteristic description pages.
    """
    target_lang = util.get_target_lang_from_request(request)

    template_name = 'characteristic/%s.pt' % (
        request.matchdict['characteristic'])
    template = util.get_zpt_template(
        template_name,
        target_lang=target_lang)
    popup_template = util.get_zpt_template(
        'macros_templates/popup.pt',
        target_lang=target_lang)

    context = {'request': request,
               'popup_template': popup_template}
    context.update(util.rtl_context_stuff(request))

    return Response(
        template.pt_render(context))
