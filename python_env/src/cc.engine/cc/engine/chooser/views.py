from webob import Response

from cc.engine import util
from cc.i18npkg import ccorg_i18n_setup


def chooser_view(request):
    template = util.get_zpt_template('chooser_pages/index.pt')
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')
    metadata_template = util.get_zpt_template(
        'macros_templates/metadata.pt')
    support_template = util.get_zpt_template(
        'macros_templates/support.pt')

    jurisdiction = util.get_selected_jurisdiction(request)

    context = {'request': request,
               'engine_template': engine_template,
               'metadata_template': metadata_template,
               'support_template': support_template,
               'selected_jurisdiction': jurisdiction}
    context.update(util.rtl_context_stuff(request))

    return Response(template.pt_render(context))
