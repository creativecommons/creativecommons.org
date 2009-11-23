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

    available_jurisdiction_codes = [
        j.code for j in util.get_selector_jurisdictions('standard')]
    
    target_lang = (
        request.matchdict.get('target_lang')
        or request.accept_language.best_matches()[0])
    active_languages = util.active_languages()

    context = {'request': request,
               'engine_template': engine_template,
               'metadata_template': metadata_template,
               'support_template': support_template,
               'selected_jurisdiction': jurisdiction,
               'available_jurisdiction_codes': available_jurisdiction_codes,
               'target_lang': target_lang,
               'active_languages': active_languages}
    context.update(util.rtl_context_stuff(request))

    return Response(template.pt_render(context))
