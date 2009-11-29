from webob import Response

from cc.engine import util
from cc.i18npkg import ccorg_i18n_setup


def _base_context(request):
    context = {
        'request': request,
        'target_lang': (
            request.matchdict.get('target_lang')
            or request.accept_language.best_matches()[0]),
        'active_languages': util.active_languages(),
        'selected_jurisdiction': util.get_selected_jurisdiction(request),
        }
    
    context.update(util.rtl_context_stuff(request))
    return context


def chooser_view(request):
    template = util.get_zpt_template('chooser_pages/index.pt')
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')
    metadata_template = util.get_zpt_template(
        'macros_templates/metadata.pt')
    support_template = util.get_zpt_template(
        'macros_templates/support.pt')

    available_jurisdiction_codes = [
        j.code for j in util.get_selector_jurisdictions('standard')]
    
    context = _base_context(request)
    context.update(
        {'engine_template': engine_template,
         'metadata_template': metadata_template,
         'support_template': support_template,
         'available_jurisdiction_codes': available_jurisdiction_codes})

    return Response(template.pt_render(context))


def choose_results_view(request):
    template = util.get_zpt_template('chooser_pages/results.pt')
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')

    context = _base_context(request)
    context.update(
        {'engine_template': engine_template})

    return Response(template.pt_render(context))
