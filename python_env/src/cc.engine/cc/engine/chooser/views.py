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


def _issue_license(request):
    """Extract the license engine fields from the request and return a
    License object."""

    request_form = request.GET or request.POST

    jurisdiction = ''
    code = ''

    license_class = 'standard'
    answers = {}

    if request_form.has_key('pd') or \
            request_form.has_key('publicdomain') or \
            request_form.get('license_code', None)  == 'publicdomain':
       # this is public domain
       license_class = 'publicdomain'

    # check for license_code
    elif request_form.has_key('license_code'):
       jurisdiction = request_form.get(
           'jurisdiction',
           request_form.get('field_jurisdiction', ''))

       license_class, answers = cc.license.support.expandLicenseCode(
           request_form.get('license_code'),
           jurisdiction = jurisdiction,
           version = request_form.get('version', None))

    # check for license_url
    elif request_form.has_key('license_url'):
        # work backwards, generating the answers from the license code
        code, jurisdiction, version = cc.license.support.expand_license_uri(
            request_form['license_url'])
        license_class, answers = cc.license.support.expandLicenseCode(
            code, jurisdiction)

        answers['version'] = version

    else:
       jurisdiction = request_form.get(
           'field_jurisdiction', jurisdiction)

       answers.update(
           {'jurisdiction': request_form.get(
                   'field_jurisdiction', jurisdiction),
            'commercial': request_form.get('field_commercial', ''),
            'derivatives': request_form.get('field_derivatives', '')})

       if request_form.get('version', False):
           answers['version'] = request_form['version']

    # add the work to the answers block
    answers.update(self._work_info(request))

    # return the license object
    return cc.license.LicenseFactory().get_class(license_class).issue(
        **answers)



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
