from lxml import etree
from urlparse import urlparse, urljoin
from urllib import quote, unquote_plus, urlencode
from StringIO import StringIO

from webob import Response, exc
from zope.i18n import translate

from cc.engine import util
from cc.engine.decorators import RestrictHttpMethods
from cc.engine.chooser.xmp_template import license_xmp_template
from cc.license._lib.functions import get_selector_jurisdictions
from cc.i18n import ccorg_i18n_setup
from cc.i18n.util import get_well_translated_langs, negotiate_locale

import cc.license
from cc.license.formatters.classes import (
    HTMLFormatter, CC0HTMLFormatter, PublicDomainHTMLFormatter,
    PDMarkHTMLFormatter)


HTML_FORMATTER = HTMLFormatter()
CC0_HTML_FORMATTER = CC0HTMLFormatter()
PUBLICDOMAIN_HTML_FORMATTER = PublicDomainHTMLFormatter()
PDMARK_HTML_FORMATTER = PDMarkHTMLFormatter()
STANDARD_SELECTOR = cc.license.LicenseSelector(
    "http://creativecommons.org/license/")


def _base_context(request, target_lang=None):
    context = {
        'request': request,
        'target_lang': (
            target_lang
            or util.get_target_lang_from_request(request)),
        'active_languages': get_well_translated_langs(),
        }

    context.update(util.rtl_context_stuff(target_lang))
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


def _formatter_work_dict(request_form):
    """
    Just pull out the very simple fields we need for the HTMLFormatter

    (Note this isn't the same as CC0HTMLFormatter)
    """
    return {
        'format': request_form.get('field_format', u''),
        'attribution_name': request_form.get('field_attribute_to_name', u''),
        'attribution_url': request_form.get('field_attribute_to_url', u''),
        'more_permissions_url': request_form.get(
            'field_morepermissionsurl', u''),
        'worktitle': request_form.get('field_worktitle', u''),
        'source_work': request_form.get('field_sourceurl', u'')}


DEFAULT_ACCEPTED = ['y', 'n']
ACCEPTED_DERIVATIVES = ['y', 'n', 'sa']

def _accept_input_and_default_y(answer, accepted=DEFAULT_ACCEPTED):
    """
    'yes' and other things need to be transformed into 'y' for
    by_answers, which barfs if it recieves things it doesn't know
    """
    if answer in accepted:
        return str(answer)
    else:
        return 'y'


def _issue_license(request_form):
    """Extract the license engine fields from the request and return a
    License object."""

    jurisdiction = request_form.get('field_jurisdiction',
                                    request_form.get('jurisdiction'))
    if jurisdiction == '-':
        jurisdiction = None

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
        return cc.license.by_uri(request_form['license_url'])

    else:
        if jurisdiction:
            # rdflib throws a hissy fit about unicode objects..
            jurisdiction = str(jurisdiction)

        ## Construct the license code for a "standard" license
        answers = {
            'commercial': _accept_input_and_default_y(
                request_form.get('field_commercial', 'y')),
            'derivatives': _accept_input_and_default_y(
                request_form.get('field_derivatives', 'y'),
                ACCEPTED_DERIVATIVES),
            'jurisdiction': jurisdiction or '',
            'version': version}

        return STANDARD_SELECTOR.by_answers(answers)


def _generate_exit_url(url, referrer, license):
    url = unquote_plus(url)

    # test if the exit_url is an absolute uri
    if urlparse(url).scheme not in ['http', 'https']:

        # this will accomodate only for 'valid' relative paths
        # e.g. foo/bar.php or /foo/bar.php?id=1, etc.
        url = urljoin(referrer, url)

    url = url.replace('[license_url]', quote(license.uri))
    url = url.replace('[license_name]', quote(license.title()))
    url = url.replace('[license_button]', quote(license.logo))
    url = url.replace('[deed_url]', quote(license.uri))

    return url


NS_CC = 'http://creativecommons.org/ns#'
NS_DC = 'http://purl.org/dc/elements/1.1/'
NS_DCQ = 'http://purl.org/dc/terms/'
NS_RDF = "http://www.w3.org/1999/02/22-rdf-syntax-ns#"
NS_RDFS = "http://www.w3.org/2000/01/rdf-schema#"
LXML_PRE_CC, LXML_PRE_DC, LXML_PRE_DCQ, LXML_PRE_RDF, LXML_PRE_RDFS = map(
    lambda ns: "{%s}" % ns,
    (NS_CC, NS_DC, NS_DCQ, NS_RDF, NS_RDFS))

NSMAP = {
    None: NS_CC,
    "dc": NS_DC,
    "dcq": NS_DCQ,
    "rdf": NS_RDF,
    "rdfs": NS_RDFS}

def _work_rdf(work_info, license):
    rdf_tree = etree.Element(
        LXML_PRE_RDF + 'rdf', nsmap = NSMAP)

    # Work subtree
    work = etree.SubElement(rdf_tree, LXML_PRE_CC + 'Work')
    work.set(LXML_PRE_RDF + 'about', work_info.get('work-url', ''))
    if work_info.get('title'):
        work_title = etree.SubElement(work, LXML_PRE_DC + 'title')
        work_title.text = work_info['title']
    if work_info.get('type'):
        work_type = etree.SubElement(work, LXML_PRE_DC + 'type')
        work_type.set(
            LXML_PRE_RDF + 'resource',
            'http://purl.org/dc/dcmitype/' + work_info['type'])
    if work_info.get('year'):
        work_year = etree.SubElement(work, LXML_PRE_DC + 'date')
        work_year.text = work_info['year']
    if work_info.get('description'):
        work_description = etree.SubElement(work, LXML_PRE_DC + 'description')
        work_description.text = work_info['description']
    if work_info.get('creator'):
        work_creator = etree.SubElement(work, LXML_PRE_DC + 'creator')
        work_creator_agent = etree.SubElement(
            work_creator, LXML_PRE_CC + 'Agent')
        work_creator_agent.text = work_info['creator']
    if work_info.get('holder'):
        work_rights = etree.SubElement(work, LXML_PRE_DC + 'rights')
        work_rights_agent = etree.SubElement(
            work_rights, LXML_PRE_CC + 'Agent')
        work_rights_agent.text = work_info['holder']
    if work_info.get('source-url'):
        work_source = etree.SubElement(work, LXML_PRE_DC + 'source')
        work_source.text = work_info['source']
    work_license = etree.SubElement(work, LXML_PRE_CC + 'license')
    work_license.set(LXML_PRE_RDF + 'resource', license.uri)
    
    license_element = etree.parse(StringIO(license.rdf)).getroot()
    rdf_tree.append(license_element)

    return etree.tostring(rdf_tree)


def chooser_view(request):
    target_lang = util.get_target_lang_from_request(request)
    context = _base_context(request, target_lang)

    if request.GET.get('partner'):
        template = util.get_zpt_template(
            'chooser_pages/partner/index.pt', target_lang)
        context['pd_get_params'] = util.publicdomain_partner_get_params(
            request.GET)
    else:
        template = util.get_zpt_template(
            'chooser_pages/index.pt', target_lang)

    engine_template = util.get_zpt_template(
        'macros_templates/engine_2cols.pt', target_lang)
    partner_template = util.get_zpt_template(
        'macros_templates/partner.pt', target_lang)
    metadata_template = util.get_zpt_template(
        'macros_templates/metadata.pt', target_lang)
    support_template = util.get_zpt_template(
        'macros_templates/support.pt', target_lang)

    available_jurisdiction_codes = [
        j.code for j in get_selector_jurisdictions('standard')
        if j.code != '']
    
    requested_jurisdiction = None
    if request.GET.has_key('jurisdiction') and \
            request.GET['jurisdiction'] in available_jurisdiction_codes:
        requested_jurisdiction = request.GET['jurisdiction']        

    # Sort the jurisdictions for the dropdown via the translated name
    jurisdictions_names = [
        (juris,
         util.unicode_cleaner(
                translate("country.%s" % juris,
                          domain=ccorg_i18n_setup.I18N_DOMAIN,
                          target_language=negotiate_locale(target_lang))))
        for juris in available_jurisdiction_codes]
    jurisdictions_names = sorted(
        jurisdictions_names, key=lambda juris: juris[1])

    context.update(
        {'engine_template': engine_template,
         'partner_template': partner_template,
         'metadata_template': metadata_template,
         'support_template': support_template,
         'jurisdictions_names': jurisdictions_names,
         'requested_jurisdiction': requested_jurisdiction,
         'referrer': request.headers.get('REFERER','')})

    return Response(template.pt_render(context))


def choose_results_view(request):
    target_lang = util.get_target_lang_from_request(request)

    if request.GET.get('partner'):
        template = util.get_zpt_template(
            'chooser_pages/partner/results.pt', target_lang)
    else:
        template = util.get_zpt_template(
            'chooser_pages/results.pt', target_lang)

    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    context = _base_context(request, target_lang)
    request_form = request.GET or request.POST

    # Special case: if anyone is linking to GPL/LGPL (mistake on old
    # deeds), redirect them to gnu.org
    if request_form.get('license_code') in ("GPL", "LGPL"):
        return exc.HTTPMovedPermanently(
            location='http://www.gnu.org/licenses/gpl-howto.html')

    # Select a license based on the request form
    license = _issue_license(request_form)

    # If the license is retired, redirect to info page
    if license.deprecated:
        # Special case: PDCC should redirect to /publicdomain/
        if license.license_code == 'publicdomain':
            return exc.HTTPMovedPermanently(location="/publicdomain/")
        else:
            return exc.HTTPMovedPermanently(location="/retiredlicenses")

    # Generate the HTML+RDFa for the license + provided work information
    work_dict = _formatter_work_dict(request_form)
    license_slim_logo = license.logo_method('80x15')

    license_html = HTML_FORMATTER.format(
        license, work_dict, target_lang)

    context.update(
        {'engine_template': engine_template,
         'license': license,
         'license_slim_logo': license_slim_logo,
         'license_title': license.title(target_lang),
         'license_html': license_html})

    if request.GET.get('partner'):
        context.update(
            {'partner_template': util.get_zpt_template(
                    'macros_templates/partner.pt', target_lang),
             'exit_url': _generate_exit_url(
                    request_form.get('exit_url', ''),
                    request_form.get('referrer', ''),
                    license)})

    return Response(template.pt_render(context))


def choose_xmp_view(request):
    request_form = request.GET or request.POST
    license = _issue_license(request_form)
    target_lang = util.get_target_lang_from_request(request)

    xmp_data = license_xmp_template(
        request_form, license, target_lang)

    return Response(
        xmp_data,
        content_type='application/xmp; charset=UTF-8',
        charset='UTF-8',
        content_disposition='attachment; filename="CC_%s.xmp' % (
            license.title().strip().replace(' ', '_')))


def get_html(request):
    target_lang = util.get_target_lang_from_request(request)

    request_form = request.GET or request.POST
    license = _issue_license(request_form)
    work_dict = _formatter_work_dict(request_form)

    license_html = HTML_FORMATTER.format(license, work_dict, target_lang)
    return Response(license_html, content_type='text/html; charset=UTF-8')


def get_rdf(request):
    request_form = request.GET or request.POST
    license = _issue_license(request_form)
    work_info = _work_info(request_form)
    rdf = _work_rdf(work_info, license)

    return Response(rdf, content_type='application/rdf+xml; charset=UTF-8')


def non_web_popup(request):
    target_lang = util.get_target_lang_from_request(request)

    request_form = request.GET or request.POST
    license = _issue_license(request_form)
    template = util.get_zpt_template(
        'chooser_pages/nonweb_popup.pt', target_lang)
    popup_template = util.get_zpt_template(
        'macros_templates/popup.pt', target_lang)
    is_publicdomain = request_form.get('publicdomain') or request_form.get('pd')
    
    context = _base_context(request, target_lang)

    context.update(
        {'popup_template': popup_template,
         'license': license,
         'is_publicdomain': is_publicdomain})

    return Response(template.pt_render(context))


def choose_wiki_redirect(request):
    return exc.HTTPMovedPermanently(
        location='/choose/results-one?license_code=by-sa')


def outdated_choosers_redirect(request):
    """
    A couple of URLs (/choose/music and /choose/sampling) are outdated
    and so should redirect to the old chooser.
    """
    return exc.HTTPMovedPermanently(
        location='/choose/')


def work_email_popup(request):
    target_lang = util.get_target_lang_from_request(request)

    request_form = request.GET or request.POST
    license = _issue_license(request_form)
    work_dict = _formatter_work_dict(request_form)

    license_html = HTML_FORMATTER.format(license, work_dict, target_lang)

    template = util.get_zpt_template(
        'chooser_pages/htmlpopup.pt', target_lang)
    popup_template = util.get_zpt_template(
        'macros_templates/popup.pt', target_lang)
    
    context = _base_context(request, target_lang)
    context.update(
        {'popup_template': popup_template,
         'license': license,
         'license_html': license_html})

    return Response(template.pt_render(context))


@RestrictHttpMethods('POST')
def work_email_send(request):
    target_lang = util.get_target_lang_from_request(request)

    request_form = request.GET or request.POST
    email_addr = request_form.get('to_email', '').decode('utf-8')
    license_name = request_form.get('license_name').decode('utf-8')
    license_html = request_form.get('license_html').decode('utf-8')

    util.send_license_info_email(
        license_name, license_html,
        email_addr, target_lang)

    template = util.get_zpt_template(
        'chooser_pages/emailhtml.pt', target_lang)
    popup_template = util.get_zpt_template(
        'macros_templates/popup.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update(
        {'request_form': request_form,
         'popup_template': popup_template})

    return Response(template.pt_render(context))


## Special choosers
## ----------------

### FSF

def gpl_redirect(request):
    """
    Redirect GPL and the LGPL to the appropriate location on gnu.org
    """
    return exc.HTTPMovedPermanently(
        location='http://www.gnu.org/licenses/gpl-howto.html')


### Public domain

def publicdomain_landing(request):
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/publicdomain/publicdomain-2.pt',
        target_lang)

    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)
    support_template = util.get_zpt_template(
        'macros_templates/support.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'support_template': support_template,
            'engine_template': engine_template})

    return Response(template.pt_render(context))


def publicdomain_confirm(request):
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/publicdomain/publicdomain-3.pt',
        target_lang)

    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    request_form = request.GET or request.POST

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'request_form': request_form})

    return Response(template.pt_render(context))


def publicdomain_result(request):
    target_lang = util.get_target_lang_from_request(request)

    request_form = request.GET or request.POST

    # make sure the user selected "confirm"
    if request_form.get('understand', False) != 'confirm':
        return exc.HTTPMovedPermanently(
            location='%s?%s' % (
                './publicdomain-3', urlencode(request.GET)))

    work_info = _work_info(request_form)
    license_html = PUBLICDOMAIN_HTML_FORMATTER.format(
        cc.license.by_code('publicdomain'),
        work_info, target_lang)

    template = util.get_zpt_template(
        'chooser_pages/publicdomain/publicdomain-4.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'request_form': request_form,
            'license_html': license_html})

    return Response(template.pt_render(context))


### -----------
### CC0 Chooser
### -----------
def cc0_landing(request):
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/zero/index.pt', target_lang)
    support_template = util.get_zpt_template(
        'macros_templates/support.pt',
        target_lang=target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'support_template': support_template})

    return Response(template.pt_render(context))


def cc0_waiver(request):
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/zero/waiver.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'country_list': util.CODE_COUNTRY_LIST})

    return Response(template.pt_render(context))


def cc0_confirm(request):
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/zero/confirm.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    request_form = request.GET or request.POST

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'request_form': request_form})

    return Response(template.pt_render(context))


def cc0_results(request):
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/zero/results.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    request_form = request.GET or request.POST

    ## Do we confirm, understand and accept the conditions of cc0?
    confirm = request_form.get('confirm', False)
    understand = request_form.get('understand', False)
    accept = request_form.get('waiver-affirm', False) and \
        request_form.get('waiver-decline', True)

    can_issue = (confirm and understand and accept)

    ## RDFA generation
    cc0_license = cc.license.by_code('CC0')
    license_html = CC0_HTML_FORMATTER.format(
        cc0_license, request_form, target_lang).strip()

    ## Did the user request an email?
    email_addr = request_form.get('email')
    successful_send = False
    if email_addr and request.method == 'POST':
        successful_send = util.send_license_info_email(
            cc0_license.title(target_lang), license_html,
            email_addr, target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'request_form': request_form,
            'can_issue': can_issue,
            'rdfa': license_html,
            'email_requested': bool(email_addr),
            'email_addr': email_addr,
            'requested_send_updates': request_form.get('send_updates', False),
            'successful_send': successful_send})

    return Response(template.pt_render(context))


def cc0_partner(request):
    """
    Partner page for CC0
    """
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/zero/partner.pt', target_lang)

    request_form = request.GET or request.POST

    cc0_license = cc.license.by_code('CC0')

    # Used for recommending PDM in case that's more appropriate
    get_params = util.publicdomain_partner_get_params(request_form)

    context = _base_context(request, target_lang)
    context.update(
        {'partner_template': util.get_zpt_template(
                'macros_templates/partner.pt', target_lang),
         'request_form': request_form,
         'get_params': get_params,
         'exit_url': _generate_exit_url(
                request_form.get('exit_url', ''),
                request_form.get('referrer', ''),
                cc0_license)})

    return Response(template.pt_render(context))


# publicdomain-direct now redirects to CC0!

def publicdomain_direct_redirect(request):
    """
    /choose/publicdomain-direct used to point to PDCC, which has been
    retired, so we redirect to CC0.
    """
    new_url = '/choose/zero/partner'

    request_form = request.GET or request.POST
    if request_form:
        new_url = '%s?%s' % (
            new_url, urlencode(request_form))
    return exc.HTTPMovedPermanently(location=new_url)


### --------------------------
### Public Domain Mark Chooser
### --------------------------

def pdmark_landing(request):
    """
    Landing page for the Public Domain Mark chooser.
    """
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/pdmark/index.pt', target_lang)
    support_template = util.get_zpt_template(
        'macros_templates/support.pt',
        target_lang=target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'support_template': support_template})

    return Response(template.pt_render(context))


def pdmark_details(request):
    """
    Details/user form page for the Public Domain Mark chooser.
    """
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/pdmark/details.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template})

    return Response(template.pt_render(context))


def pdmark_results(request):
    """
    Results page for the Public Domain Mark chooser.

    Includes the user's RDFa copy-paste html.
    Also handles email sending if the user requests it.
    """
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/pdmark/results.pt', target_lang)
    engine_template = util.get_zpt_template(
        'macros_templates/engine.pt', target_lang)

    request_form = request.GET or request.POST

    ## RDFA generation
    license = cc.license.by_code('mark')
    license_html = PDMARK_HTML_FORMATTER.format(
        license, request_form, target_lang).strip()

    ## Did the user request an email?
    email_addr = request_form.get('email')
    successful_send = False
    if email_addr and request.method == 'POST':
        successful_send = util.send_license_info_email(
            license.title(target_lang), license_html,
            email_addr, target_lang)

    context = _base_context(request, target_lang)
    context.update({
            'engine_template': engine_template,
            'request_form': request_form,
            'rdfa': license_html,
            'email_requested': bool(email_addr),
            'email_addr': email_addr,
            'successful_send': successful_send,
            'requested_send_updates': request_form.get('send_updates', False)})

    return Response(template.pt_render(context))


def pdmark_partner(request):
    """
    Partner page for PDM
    """
    target_lang = util.get_target_lang_from_request(request)

    template = util.get_zpt_template(
        'chooser_pages/pdmark/partner.pt', target_lang)

    request_form = request.GET or request.POST

    pdm_license = cc.license.by_code('mark')

    # Used for recommending CC0 in case that's more appropriate
    get_params = util.publicdomain_partner_get_params(request_form)
    
    context = _base_context(request, target_lang)
    context.update(
        {'partner_template': util.get_zpt_template(
                'macros_templates/partner.pt', target_lang),
         'request_form': request_form,
         'get_params': get_params,
         'exit_url': _generate_exit_url(
                request_form.get('exit_url', ''),
                request_form.get('referrer', ''),
                pdm_license)})

    return Response(template.pt_render(context))
