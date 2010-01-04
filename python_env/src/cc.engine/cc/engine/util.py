import re
import os
import pkg_resources

import RDF
from lxml import etree
from webob import Response
from zope.component.globalregistry import base
from zope.i18n.interfaces import ITranslationDomain
from zope.i18n.translationdomain import TranslationDomain
from zope.i18n import translate

import cc.license
from cc.license import selectors
from cc.license._lib import rdf_helper
from cc.license.formatters.pagetemplate import CCLPageTemplateFile
from cc.i18npkg import ccorg_i18n_setup


BASE_TEMPLATE_DIR = os.path.join(os.path.dirname(__file__), 'templates')

PERMITS_NAME_MAP = {
    "http://creativecommons.org/ns#DerivativeWorks": "nd",
    }

LANGUAGE_JURISDICTION_MAPPING = {}


class Error(Exception): pass


### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
### Special ZPT unit test hackery begins HERE
### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

ZPT_TEST_ENABLED = False
ZPT_TEST_TEMPLATES = {}
class CCLPageTemplateFileTester(CCLPageTemplateFile):
    def pt_render(self, namespace, *args, **kwargs):
        ZPT_TEST_TEMPLATES[self.filename] = namespace
        CCLPageTemplateFile.pt_render(self, namespace, *args, **kwargs)

def _activate_zpt_testing():
    global ZPT_TEST_ENABLED
    ZPT_TEST_ENABLED = True

### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
### </Special ZPT unit test hackery>
### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


def locale_to_cclicense_style(locale):
    split_locale = locale.split('-')
    new_locale = split_locale[0].lower()
    if len(split_locale) == 2:
        new_locale = new_locale + u'_' + split_locale[1].upper()

    return new_locale


def full_zpt_filename(template_path):
    return os.path.join(BASE_TEMPLATE_DIR, template_path)


def get_zpt_template(template_path, target_lang=None):
    full_template_path = full_zpt_filename(template_path)

    if ZPT_TEST_ENABLED:
        ptf_class = CCLPageTemplateFileTester
    else:
        ptf_class = CCLPageTemplateFile

    return ptf_class(
        full_template_path, target_language=target_lang)
    

def get_locale_file_from_lang_matches(lang_matches):
    """
    Iterate through a series of language matches and pick the first
    one that has a file associated with it, if any

    Returns a tuple of (lang, locale_filename) or (None, None) if
    nothing found
    """
    for lang in lang_matches:
        language = locale_to_cclicense_style(lang)

        this_locale_filename = pkg_resources.resource_filename(
            u'zope.i18n.locales', u'data/%s.xml' % language)

        if os.path.exists(this_locale_filename):
            return lang, this_locale_filename

    return None, None


def _get_xpath_attribute(etree, path, attribute):
    """
    Get an attribute from a node grabbed from xpath.
    If not found, return None.
    """
    try:
        return etree.xpath(path)[0].attrib[attribute]
    except IndexError, KeyError:
        return None


def get_locale_identity_data(request):
    """
    Get the identity data for a locale
    """
    lang, locale_filename = get_locale_file_from_lang_matches(
        request.accept_language.best_matches())
    
    if not locale_filename:
        return {}

    locale_tree = etree.parse(file(locale_filename))
    identity_data = {}
    identity_data['language'] = _get_xpath_attribute(
        locale_tree, '/ldml/identity/language', 'type')
    identity_data['script'] = _get_xpath_attribute(
        locale_tree, '/ldml/identity/script', 'type')
    identity_data['territory'] = _get_xpath_attribute(
        locale_tree, '/ldml/identity/territory', 'type')
    identity_data['variant'] = _get_xpath_attribute(
        locale_tree, '/ldml/identity/variant', 'type')
    
    return identity_data


def get_locale_text_orientation(request):
    """
    Find out whether the locale is ltr or rtl
    """
    lang, locale_filename = get_locale_file_from_lang_matches(
        request.accept_language.best_matches())

    if not locale_filename:
        return u'ltr'

    locale_tree = etree.parse(file(locale_filename))
    try:
        char_orientation = locale_tree.xpath(
            '/ldml/layout/orientation')[0].attrib['characters']
        if char_orientation == u'right-to-left':
            return u'rtl'
        else:
            return u'ltr'
    except IndexError:
        return u'ltr'


def get_license_conditions(license, target_language="en_US"):
    """
    This is for compatibility with the way the old cc.engine handled
    conditions on the deeds page.  It kinda sucks... I think we could
    do better with the new api.
    """
    attrs = []

    for lic in license.license_code.split('-'):

        # bail on sampling
        if lic.find('sampling') > -1:
            continue
        
        # Go through the chars and build up the HTML and such
        char_title = unicode_cleaner(
            translate(
                'char.%s_title' % lic,
                domain=ccorg_i18n_setup.I18N_DOMAIN,
                target_language=target_language))
        char_brief = unicode_cleaner(
            translate(
                'char.%s_brief' % lic,
                domain=ccorg_i18n_setup.I18N_DOMAIN,
                target_language=target_language))

        icon_name = lic
        predicate = 'cc:requires'
        object = 'http://creativecommons.org/ns#Attribution'

        if lic == 'nc':
            predicate = 'cc:prohibits'
            object = 'http://creativecommons.org/ns#CommercialUse'
        elif lic == 'sa':
            object = 'http://creativecommons.org/ns#ShareAlike'
            if license.version == 3.0 and license.code == 'by-sa':
                char_brief = unicode_cleaner(
                    translate(
                        'char.sa_bysa30_brief',
                        domain=ccorg_i18n_setup.I18N_DOMAIN,
                        target_language=target_language))
        elif lic == 'nd':
            predicate = ''
            object = ''

        attrs.append(
            {'char_title': char_title,
             'char_brief': char_brief,
             'icon_name': icon_name,
             'char_code': lic,
             'predicate': predicate,
             'object': object})

    return attrs


def get_valid_jurisdictions(license_class='standard'):
    # TODO: use license_class here
    query = RDF.Query(
        str('PREFIX cc: <http://creativecommons.org/ns#> '
            'SELECT ?jurisdiction WHERE '
            '{ ?license cc:licenseClass <http://creativecommons.org/license/> .'
            '  ?license cc:jurisdiction ?jurisdiction }'),
        query_language="sparql")

    jurisdictions = set(
        [unicode(result['jurisdiction'].uri)
         for result in query.execute(rdf_helper.ALL_MODEL)])

    return jurisdictions


def active_languages():
    """Return a sequence of dicts, where each element consists of the
    following keys:

    * code: the language code
    * name: the translated name of this language

    for each available language."""
    # get a list of avaialable translations
    domain = base.queryUtility(ITranslationDomain, ccorg_i18n_setup.I18N_DOMAIN)
    lang_codes = set(domain.getCatalogsInfo().keys())

    # determine the intersection of available translations and
    # launched jurisdiction locales
    launched_locales = set()
    jurisdictions = get_valid_jurisdictions()

    for jurisdiction in jurisdictions:
        query_string = (
            'PREFIX dc: <http://purl.org/dc/elements/1.1/> '
            'SELECT ?title WHERE {'
            '  <%s> dc:title ?title}') % jurisdiction

        query = RDF.Query(
            str(query_string),
            query_language='sparql')
        this_juri_locales = set(
            [result['title'].literal_value['language']
             for result in query.execute(rdf_helper.JURI_MODEL)])

        # Append those locales that are applicable to this domain
        launched_locales.update(lang_codes.intersection(this_juri_locales))

    # make our sequence have a predictable order
    launched_locales = list(launched_locales)
    launched_locales.sort()

    # this loop is long hand for clarity; it's only done once, so
    # the additional performance cost should be negligible
    result = []
    for code in lang_codes:

        if code == 'test': continue

        name = domain.translate('lang.%s' % code, target_language=code).\
            decode('utf-8')
        if name != u'lang.%s' % code:
            # we have a translation for this name...
            result.append(dict(code=code, name=name))

    return result


def unicode_cleaner(string):
    if isinstance(string, unicode):
        return string

    try:
        return string.decode('utf-8')
    except UnicodeError:
        try:
            return string.decode('latin-1')
        except UnicodeError:
            return string.decode('utf-8', 'ignore')


def rtl_context_stuff(request):
    """
    This is to accomodate the old templating stuff, which requires:
     - text_orientation
     - is_rtl
     - is_rtl_align

    We could probably adjust the templates to just use
    text_orientation but maybe we'll do that later.
    """
    text_orientation = get_locale_text_orientation(request)

    # 'rtl' if the request locale is represented right-to-left;
    # otherwise an empty string.
    is_rtl = text_orientation == 'rtl'

    # Return the appropriate alignment for the request locale:
    # 'text-align:right' or 'text-align:left'.
    if text_orientation == 'rtl':
        is_rtl_align = 'text-align: right'
    else:
        is_rtl_align = 'text-align: left'

    return {'get_ltr_rtl': text_orientation,
            'is_rtl': is_rtl,
            'is_rtl_align': is_rtl_align}
    
    
def plain_template_view(template_name, request):
    """
    Not an actual view, but used to build these more tedious views
    """
    template = get_zpt_template(template_name)
    engine_template = get_zpt_template(
        'macros_templates/engine.pt')

    context = {'request': request,
               'engine_template': engine_template}
    context.update(rtl_context_stuff(request))

    return Response(
        template.pt_render(context))


def get_selected_jurisdiction(request):
    try:
        lang = request.accept_language.best_matches()[0]
    except IndexError:
        return '-'

    lang_country = re.split('[-_]', lang)

    if len(lang_country) == 2:
        lang = (lang_country[0] + '-' + lang_country[1]).lower()

    lang_jurisdiction_map = get_language_jurisdiction_map()

    # see if we have a result with the entire lang
    result = lang_jurisdiction_map.get(lang)
    if result:
        return result

    # if not, see if we have a result with just the first half of the
    # lang-country split, and if not that, return '-'
    return lang_jurisdiction_map.get(lang_country[0].lower()) or '-'


def get_language_jurisdiction_map():
    global LANGUAGE_JURISDICTION_MAPPING
    if LANGUAGE_JURISDICTION_MAPPING:
        return LANGUAGE_JURISDICTION_MAPPING
    
    qstring = "\n".join(
        ["PREFIX cc: <http://creativecommons.org/ns#>",
         "PREFIX dc: <http://purl.org/dc/elements/1.1/>",
         "SELECT ?jurisdiction ?language",
         "WHERE {?jurisdiction dc:language ?language}"])

    query = RDF.Query(qstring, query_language="sparql")

    juri_lang_data = [
        (unicode(r['jurisdiction']).rstrip(']/').split('/')[-1],
         unicode(r['language']))
        for r in query.execute(rdf_helper.JURI_MODEL)]

    for juri, lang in juri_lang_data:
        LANGUAGE_JURISDICTION_MAPPING[lang] = juri

    return LANGUAGE_JURISDICTION_MAPPING


def get_selector_jurisdictions(selector_name='standard'):
    """

    """
    selector = selectors.choose(selector_name)
    qstring = "\n".join(
        ["SELECT ?license",
         "WHERE (?license cc:licenseClass <%s>)" % str(selector.uri),
         "USING cc FOR <http://creativecommons.org/ns#>"])
    query = RDF.Query(qstring, query_language="rdql")

    # This is so stupid, but if we add a WHERE clause for
    # jurisdictions in the query string it takes approximately 5
    # million years.
    licenses = [
        cc.license.by_uri(str(result['license'].uri))
        for result in query.execute(rdf_helper.ALL_MODEL)]
    jurisdictions = set([license.jurisdiction for license in licenses])
    jurisdictions = [juri for juri in jurisdictions if juri.launched]
    return jurisdictions


class UnsafeResource(Error): pass

def safer_resource_filename(package, resource):
    """
    Prevent "../../../../etc/passwd"-like resource_filename attempts
    """
    filename = os.path.abspath(
        pkg_resources.resource_filename(package, resource))
    if not filename.startswith(pkg_resources.resource_filename(package, '')):
        raise UnsafeResource("Resource resolves outside of package")

    return filename
