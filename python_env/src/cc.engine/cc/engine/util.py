import csv
import os
import pkg_resources
import string
import smtplib
import urllib

from email.MIMEText import MIMEText
import email.Charset
email.Charset.add_charset('utf-8', email.Charset.SHORTEST, None, None)

import RDF
from lxml import etree
from webob import Response
from zope.component.globalregistry import base
from zope.i18n.interfaces import ITranslationDomain
from zope.i18n.translationdomain import TranslationDomain
from zope.i18n import translate
from zope.i18nmessageid import MessageFactory

from cc.license._lib import rdf_helper
from cc.license._lib import functions as cclicense_functions
from cc.i18n import ccorg_i18n_setup

from cc.engine.pagetemplate import CCLPageTemplateFile


_ = MessageFactory('cc_org')


BASE_TEMPLATE_DIR = os.path.join(os.path.dirname(__file__), 'templates')

PERMITS_NAME_MAP = {
    "http://creativecommons.org/ns#DerivativeWorks": "nd",
    }

LANGUAGE_JURISDICTION_MAPPING = {}


class Error(Exception): pass


TESTS_ENABLED = False
def _activate_testing():
    """
    Call this to activate testing in util.py
    """
    global TESTS_ENABLED
    TESTS_ENABLED = True


### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
### Special ZPT unit test hackery begins HERE
### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

ZPT_TEST_TEMPLATES = {}
class CCLPageTemplateFileTester(CCLPageTemplateFile):
    def pt_render(self, namespace, *args, **kwargs):
        ZPT_TEST_TEMPLATES[self.filename] = namespace
        return CCLPageTemplateFile.pt_render(self, namespace, *args, **kwargs)

def _clear_zpt_test_templates():
    ZPT_TEST_TEMPLATES.clear()

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

    if TESTS_ENABLED:
        ptf_class = CCLPageTemplateFileTester
    else:
        ptf_class = CCLPageTemplateFile

    return ptf_class(
        full_template_path, target_language=target_lang)
    

def get_locale_file_from_locale(locale):
    """
    Returns the path to the locale file as a string or None if
    that file does not exist.
    """
    language = locale_to_cclicense_style(locale)

    this_locale_filename = pkg_resources.resource_filename(
        u'zope.i18n.locales', u'data/%s.xml' % language)

    if os.path.exists(this_locale_filename):
        return this_locale_filename
    else:
        return None
        

def _get_xpath_attribute(etree, path, attribute):
    """
    Get an attribute from a node grabbed from xpath.
    If not found, return None.
    """
    try:
        return etree.xpath(path)[0].attrib[attribute]
    except IndexError, KeyError:
        return None


# XXX: Apparently this is deprecated, I guess?  Keeping for now until
#   we're sure we don't need it.
def get_locale_identity_data(locale):
    """
    Get the identity data for a locale
    """
    locale_filename = get_locale_file_from_locale(locale)
    
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


def get_locale_text_orientation(locale):
    """
    Find out whether the locale is ltr or rtl
    """
    locale_filename = get_locale_file_from_locale(locale)

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


def subset_dict(orig_dict, subset_keys):
    """
    Take a dictionary, return a subset of it based on a list of
    allowed keys.

    Args:
      - orig_dict: the dictionary to subset
      - subset_keys: a list of keys that are allowed in the new dictionary

    Returns:
      A new dictionary with only the subset keys
    """
    new_dict = {}

    for key in subset_keys:
        if orig_dict.has_key(key):
            new_dict[key] = orig_dict[key]

    return new_dict


def publicdomain_partner_get_params(request_form):
    """
    Take a request form (GET or POST parameters) and use it to
    generate an appropriate urlencoded get parameters to link with on
    a partner interface to the CC0 and/or PDM partner pages
    """
    get_params_dict = subset_dict(
        request_form,
        ['lang', 'partner', 'exit_url', 'stylesheet'])

    get_params = urllib.urlencode(get_params_dict)

    return get_params


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


_ACTIVE_LANGUAGES = None

def active_languages():
    """Return a sequence of dicts, where each element consists of the
    following keys:

    * code: the language code
    * name: the translated name of this language

    for each available language."""
    global _ACTIVE_LANGUAGES
    if _ACTIVE_LANGUAGES:
        return _ACTIVE_LANGUAGES

    # get a list of avaialable translations
    domain = base.queryUtility(ITranslationDomain, ccorg_i18n_setup.I18N_DOMAIN)
    lang_codes = set(domain.getCatalogsInfo().keys())

    # determine the intersection of available translations and
    # launched jurisdiction locales
    launched_locales = set()
    jurisdictions = cclicense_functions.get_valid_jurisdictions()

    for jurisdiction in jurisdictions:
        query_string = (
            'PREFIX dc: <http://purl.org/dc/elements/1.1/> '
            'SELECT ?lang WHERE {'
            '  <%s> dc:language ?lang}') % jurisdiction

        query = RDF.Query(
            str(query_string),
            query_language='sparql')
        this_juri_locales = set(
            [make_locale_lower_upper_style(str(result['lang']))
             for result in query.execute(rdf_helper.JURI_MODEL)])

        # Append those locales that are applicable to this domain
        launched_locales.update(lang_codes.intersection(this_juri_locales))

    # XXX: Have to hack in Esperanto here because it's technically an
    # "Unported" language but there is no unported RDF jurisdiction in
    # jurisdictions.rdf..
    launched_locales.add('eo')

    # make our sequence have a predictable order
    launched_locales = list(launched_locales)

    # this loop is long hand for clarity; it's only done once, so
    # the additional performance cost should be negligible
    result = []
    for code in launched_locales:

        if code == 'test': continue

        name = domain.translate(u'lang.%s' % code, target_language=code)
        if name != u'lang.%s' % code:
            # we have a translation for this name...
            result.append(dict(code=code, name=name))

    result = sorted(result, key=lambda lang: lang['name'].lower())

    _ACTIVE_LANGUAGES = result

    return result


def get_all_license_urls():
    qstring = """
              PREFIX cc: <http://creativecommons.org/ns#>
              PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>

              SELECT ?luri
              WHERE {
                     ?luri rdf:type cc:License .
                    }
              """
    query = RDF.Query(qstring, query_language='sparql')
    solns = list(query.execute(rdf_helper.ALL_MODEL))
    return tuple(str(s['luri'].uri) for s in solns)


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


def rtl_context_stuff(locale):
    """
    This is to accomodate the old templating stuff, which requires:
     - text_orientation
     - is_rtl
     - is_rtl_align

    We could probably adjust the templates to just use
    text_orientation but maybe we'll do that later.
    """
    text_orientation = get_locale_text_orientation(locale)

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
    target_lang = get_target_lang_from_request(request)

    template = get_zpt_template(
        template_name,
        target_lang=target_lang)
    engine_template = get_zpt_template(
        'macros_templates/engine.pt',
        target_lang=target_lang)

    context = {'request': request,
               'engine_template': engine_template}
    context.update(rtl_context_stuff(target_lang))

    return Response(
        template.pt_render(context))


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


### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
### Special email test stuff begins HERE
### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

# We have two "test inboxes" here:
# 
# EMAIL_TEST_INBOX:
# ----------------
#   If you're writing test views, you'll probably want to check this.
#   It contains a list of MIMEText messages.
#
# EMAIL_TEST_MBOX_INBOX:
# ----------------------
#   This collects the messages from the FakeMhost inbox.  It's reslly
#   just here for testing the send_email method itself.
#
#   Anyway this contains:
#    - from
#    - to: a list of email recipient addresses
#    - message: not just the body, but the whole message, including
#      headers, etc.
#
# ***IMPORTANT!***
# ----------------
# Before running tests that call functions which send email, you should
# always call _clear_test_inboxes() to "wipe" the inboxes clean. 

EMAIL_TEST_INBOX = []
EMAIL_TEST_MBOX_INBOX = []


class FakeMhost(object):
    """
    Just a fake mail host so we can capture and test messages
    from send_email
    """
    def connect(self):
        pass

    def sendmail(self, from_addr, to_addrs, message):
        EMAIL_TEST_MBOX_INBOX.append(
            {'from': from_addr,
             'to': to_addrs,
             'message': message})

def _clear_test_inboxes():
    global EMAIL_TEST_INBOX
    global EMAIL_TEST_MBOX_INBOX
    EMAIL_TEST_INBOX = []
    EMAIL_TEST_MBOX_INBOX = []

### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
### </Special email test stuff>
### ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

def send_email(from_addr, to_addrs, subject, message_body):
    # TODO: make a mock mhost if testing is enabled
    if TESTS_ENABLED:
        mhost = FakeMhost()
    else:
        mhost = smtplib.SMTP()

    mhost.connect()

    message = MIMEText(message_body.encode('utf-8'), 'plain', 'utf-8')
    message['Subject'] = subject
    message['From'] = from_addr
    message['To'] = ', '.join(to_addrs)

    if TESTS_ENABLED:
        EMAIL_TEST_INBOX.append(message)

    return mhost.sendmail(from_addr, to_addrs, message.as_string())


LICENSE_INFO_EMAIL_BODY = _(
    'license.info_email_body',
    """Thank you for using a Creative Commons legal tool for your work.

You have selected ${license_title}.
You should include a reference to this on the web page that includes
the work in question.

Here is the suggested HTML:

${license_html}

Tips for marking your work can be found at
http://wiki.creativecommons.org/Marking.  Information on the supplied HTML and
metadata can be found at http://wiki.creativecommons.org/CC_REL.

Thank you!
Creative Commons Support
info@creativecommons.org""")

LICENSE_INFO_EMAIL_SUBJECT = _(
    'license.info_email_subject',
    'Your Creative Commons License Information')


def send_license_info_email(license_title, license_html,
                            recipient_email, locale):
    """
    Send license information email to a user.

    Arguments:
     - license_title: title of the license
     - license_html: copy-paste license HTML
     - recipient_email: the user that is getting this email
     - locale: language email should be sent in

    Returns:
      A boolean specifying whether or not the email sent successfully
    """

    email_body = string.Template(
        translate(LICENSE_INFO_EMAIL_BODY, target_language=locale)).substitute(
        {'license_title': license_title,
         'license_html': license_html})

    try:
        send_email(
            'info@creativecommons.org', [recipient_email],
            translate(LICENSE_INFO_EMAIL_SUBJECT, target_language=locale),
            email_body)
        return True
    except smtplib.SMTPException:
        return False


def make_locale_lower_upper_style(locale):
    if '-' in locale:
        lang, country = locale.split('-', 1)
        return '%s_%s' % (lang.lower(), country.upper())
    elif '_' in locale:
        lang, country = locale.split('_', 1)
        return '%s_%s' % (lang.lower(), country.upper())
    else:
        return locale.lower()


def get_target_lang_from_request(request):
    request_form = request.GET or request.POST

    if request_form.has_key('lang'):
        return make_locale_lower_upper_style(request_form['lang'])

    accept_lang_matches = request.accept_language.best_matches()
    if request.matchdict.has_key('target_lang'):
        target_lang = request.matchdict['target_lang']
    elif accept_lang_matches:
        target_lang = accept_lang_matches[0]
    else:
        target_lang = 'en'

    return make_locale_lower_upper_style(target_lang)


###
## ISO 3166 -- country names to country code utilities
###

CODE_COUNTRY_LIST = [
    (unicode_cleaner(code), unicode_cleaner(country))
    for code, country in csv.reader(
        file(pkg_resources.resource_filename('cc.engine', 'iso3166.csv')))]
CODE_COUNTRY_MAP = dict(CODE_COUNTRY_LIST)
