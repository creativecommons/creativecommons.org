from urlparse import urlparse, urljoin

from webob import Response, exc

from cc.engine import util
from cc.i18npkg import ccorg_i18n_setup
import cc.license
from cc.license.formatters.classes import HTMLFormatter


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


def _work_info(request_form):
    """Extract work information from the request and return it as a
    dict."""

    result = {'title' : u'',
              'creator' : u'',
              'copyright_holder' : u'',
              'copyright_year' : u'',
              'description' : u'',
              'format' : u'',
              'type' : u'',
              'work_url' : u'',
              'source_work_url' : u'',
              'source_work_domain' : u'',
              'attribution_name' : u'',
              'attribution_url' : u'',
              'more_permissions_url' : u'',
              }

    # look for keys that match the param names
    for key in request_form:
        if key in result:
            result[key] = request_form[key]

    # look for keys from the license chooser interface

    # work title
    if request_form.has_key('field_worktitle'):
        result['title'] = request_form['field_worktitle']

    # creator
    if request_form.has_key('field_creator'):
        result['creator'] = request_form['field_creator']

    # copyright holder
    if request_form.has_key('field_copyrightholder'):
        result['copyright_holder'] = result['holder'] = \
            request_form['field_copyrightholder']
    if request_form.has_key('copyright_holder'):
        result['holder'] = request_form['copyright_holder']

    # copyright year
    if request_form.has_key('field_year'):
        result['copyright_year'] = result['year'] = request_form['field_year']
    if request_form.has_key('copyright_year'):
        result['year'] = request_form['copyright_year']

    # description
    if request_form.has_key('field_description'):
        result['description'] = request_form['field_description']

    # format
    if request_form.has_key('field_format'):
        result['format'] = result['type'] = request_form['field_format']

    # source url
    if request_form.has_key('field_sourceurl'):
        result['source_work_url'] = result['source-url'] = \
            request_form['field_sourceurl']

        # extract the domain from the URL
        result['source_work_domain'] = urlparse(
            result['source_work_url'])[1]

        if not(result['source_work_domain'].strip()):
            result['source_work_domain'] = result['source_work_url']

    # attribution name
    if request_form.has_key('field_attribute_to_name'):
        result['attribution_name'] = request_form['field_attribute_to_name']

    # attribution URL
    if request_form.has_key('field_attribute_to_url'):
        result['attribution_url'] = request_form['field_attribute_to_url']

    # more permissions URL
    if request_form.has_key('field_morepermissionsurl'):
        result['more_permissions_url'] = request_form['field_morepermissionsurl']

    return result


def _issue_license(request_form):
    """Extract the license engine fields from the request and return a
    License object."""
    jurisdiction = request_form.get('field_jurisdiction')
    version = request_form.get('version', None)

    # Handle public domain class
    if request_form.has_key('pd') or \
            request_form.has_key('publicdomain') or \
            request_form.get('license_code', None) == 'publicdomain':
        return cc.license.by_code('publicdomain')

    # check for license_code
    elif request_form.has_key('license_code'):
        return cc.license.by_code(
            request_form['license_code'],
            jurisdiction=jurisdiction,
            version=version)

    # check for license_url
    elif request_form.has_key('license_url'):
        return cc.license.by_url(request_form['license_url'])

    else:
        ## Construct the license code for a "standard" license
        attribution = request_form.get('field_attribution')
        commercial = request_form.get('field_commercial')
        derivatives = request_form.get('field_derivatives')

        license_code_bits = []
        if not attribution == 'n':
            license_code_bits.append('by')

        if commercial == 'n':
            license_code_bits.append('nc')

        if derivatives == 'n':
            license_code_bits.append('nd')
        elif derivatives == 'sa':
            license_code_bits.append('sa')

        license_code = '-'.join(license_code_bits)
        return cc.license.by_code(
            license_code,
            jurisdiction=jurisdiction,
            version=version)


def chooser_view(request):
    if request.GET.get('partner'):
        template = util.get_zpt_template('chooser_pages/partner/index.pt')
    else:
        template = util.get_zpt_template('chooser_pages/index.pt')

    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')
    partner_template = util.get_zpt_template(
        'macros_templates/partner.pt')
    metadata_template = util.get_zpt_template(
        'macros_templates/metadata.pt')
    support_template = util.get_zpt_template(
        'macros_templates/support.pt')

    available_jurisdiction_codes = [
        j.code for j in util.get_selector_jurisdictions('standard')]
    
    context = _base_context(request)

    context.update(
        {'engine_template': engine_template,
         'partner_template': partner_template,
         'metadata_template': metadata_template,
         'support_template': support_template,
         'available_jurisdiction_codes': available_jurisdiction_codes})

    return Response(template.pt_render(context))


def choose_results_view(request):
    template = util.get_zpt_template('chooser_pages/results.pt')
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt')

    context = _base_context(request)
    request_form = request.GET or request.POST
    license = _issue_license(request_form)
    work_info = _work_info(request_form)
    license_slim_logo = license.logo_method('80x15')

    html_formatter = HTMLFormatter()
    license_html = html_formatter.format(license, work_info)

    context.update(
        {'engine_template': engine_template,
         'license': license,
         'license_slim_logo': license_slim_logo,
         'license_html': license_html})

    return Response(template.pt_render(context))


def choose_wiki_redirect(request):
    return exc.HTTPTemporaryRedirect(
        location='/choose/results-one?license_code=by-sa')
