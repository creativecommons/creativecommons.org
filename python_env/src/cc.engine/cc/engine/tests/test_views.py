import cgi
import pkg_resources
import urlparse
import urllib
import unittest
from lxml import html as lxml_html
import StringIO
from poster.encode import multipart_encode
from poster.streaminghttp import register_openers
import urllib2
import shutil
import tempfile
import os.path

try:
    import json
except ImportError:
    import simplejson as json

import webtest
from nose.tools import assert_equal

from cc.engine import app, staticdirect, util
import cc.license

util._activate_testing()


### ----------
### Exceptions
### ----------

class StringTestFailed(AssertionError): pass


### ---------------
### routing testing
### ---------------

def test_routing():
    pass


### ------------
### view testing
### ------------


TESTAPP = webtest.TestApp(
    app.CCEngineApp(
        staticdirect.RemoteStaticDirect('/static/'), {}))


# XXX: Move to test_app?
def test_404():
    response = TESTAPP.get('/haha-i-dont-exist/', status=404)

    # You'd better be sorry!
    assert u'Sorry...' in response.unicode_body


def test_root_view():
    response = TESTAPP.get('/')
    assert_equal(response.body, 'This is the root')


## Deed view tests


def _deed_tester(url, template_path,
                 expected_code, expected_version, expected_jurisdiction,
                 expected_license):
    response = TESTAPP.get(url)
    namespace = util.TEST_TEMPLATE_CONTEXT.pop(template_path)
    request = namespace['request']
    assert_equal(namespace['license'], expected_license)
    assert_equal(request.matchdict.get('code'), expected_code)
    assert_equal(request.matchdict.get('version'), expected_version)
    assert_equal(request.matchdict.get('jurisdiction'), expected_jurisdiction)


def test_standard_deeds_licenses():
    """
    Make sure the correct licenses get selected from the deeds
    """
    _deed_tester(
        '/licenses/by/3.0/', 'licenses/standard_deed.html',
        'by', '3.0', None,
        cc.license.by_code('by', version='3.0'))
    _deed_tester(
        '/licenses/by-sa/3.0/', 'licenses/standard_deed.html',
        'by-sa', '3.0', None,
        cc.license.by_code('by-sa'))


def test_deed_legalcodes():
    def get_legalcode_links(request_url):
        """
        Return [(link_href, stripped_link_text)]
        """
        response = TESTAPP.get(request_url)
        response_tree = lxml_html.parse(
            StringIO.StringIO(response.unicode_body))
        return [
            (el.attrib['href'], el.text.strip())
            for el in response_tree.xpath(
                "id('legalcode-block')/"
                "div[@id='deed-disclaimer']/"
                "div[@class='summary']//a")]

    # Standard, single-legalcode
    # Maybe test for absolute urls later :\
    assert_equal(
        get_legalcode_links('/licenses/by/3.0/'),
        [('legalcode', 'Legal Code (the full license)')])

    # Multilegal, english
    assert_equal(
        get_legalcode_links('/licenses/by/2.5/es/deed.en'),
        [('http://creativecommons.org/licenses/by/2.5/es/legalcode.eu',
          u'Basque'),
         ('http://creativecommons.org/licenses/by/2.5/es/legalcode.ca',
          u'Catalan'),
         ('http://creativecommons.org/licenses/by/2.5/es/legalcode.gl',
          u'Galician'),
         ('http://creativecommons.org/licenses/by/2.5/es/legalcode.es',
          u'Spanish')])
    
    # Multilegal, non-english
    assert_equal(
        get_legalcode_links('/licenses/by/2.5/es/deed.es'),
        [('http://creativecommons.org/licenses/by/2.5/es/legalcode.es',
          u'Castellano'),
         ('http://creativecommons.org/licenses/by/2.5/es/legalcode.ca',
          u'Catal\xe1n'),
         ('http://creativecommons.org/licenses/by/2.5/es/legalcode.gl',
          u'Gallego'),
         ('http://creativecommons.org/licenses/by/2.5/es/legalcode.eu',
          u'Vasco')])


## RDF view tests
RDF_HEADER = 'application/rdf+xml; charset=UTF-8'

def _rdf_tester(url, rdf_file):
    response = TESTAPP.get(url)
    rdf_file_contents = util.unicode_cleaner(
        file(pkg_resources.resource_filename(
                'cc.licenserdf', rdf_file)).read())
    assert_equal(response.headers['Content-Type'], RDF_HEADER)
    assert_equal(response.unicode_body, rdf_file_contents)

def test_rdf_views():
    _rdf_tester(
        '/licenses/by-sa/2.0/rdf',
        'licenses/creativecommons.org_licenses_by-sa_2.0_.rdf')
    _rdf_tester(
        '/licenses/by/3.0/rdf',
        'licenses/creativecommons.org_licenses_by_3.0_.rdf')

    _rdf_tester(
        '/licenses/MIT/rdf',
        'licenses/creativecommons.org_licenses_MIT_.rdf')
    _rdf_tester(
        '/licenses/BSD/rdf',
        'licenses/creativecommons.org_licenses_BSD_.rdf')
    

VIEWS_TEST_DATA = json.load(
    file(pkg_resources.resource_filename(
            'cc.engine.tests', 'view_tests.json')))


def test_all_views_simple():
    """
    Test all views by checking with the JSON data.

    Possible parameters for view data in the JSON file and what will
    be tested if present:
     - path: *required*.  Simple test that the page loads (or
       redirects) will be done.
     - string_tests: an array of strings that will be checked for
       presence in the body of the response.
    """
    for view in VIEWS_TEST_DATA:
        view_result = TESTAPP.get(view['path'])

        if view.has_key('string_tests'):
            for string_test in view['string_tests']:
                if not string_test in view_result.unicode_body:
                    raise StringTestFailed(
                        'On path "%s" string test failed for: "%s"' % (
                            view['path'], string_test))


def test_license_to_choose_redirect():
    # Make sure we redirect from /license/* to /choose/ and keep the
    # GET parameters
    response = TESTAPP.get(
        '/license/zero/results?'
        'license-class=zero&name=ZeroMan&work_title=SubZero')
    redirected_response = response.follow()
    assert_equal(urlparse.urlsplit(response.location)[2], '/choose/zero/results')
    qs = cgi.parse_qs(urlparse.urlsplit(response.location)[3])
    assert_equal(
        qs,
        {'license-class': ['zero'],
         'name': ['ZeroMan'],
         'work_title': ['SubZero']})

    # Also make sure that POST redirects work
    response = TESTAPP.post(
        '/license/zero/results',
        {'license-class': 'zero',
         'name': 'ZeroMan',
         'work_title': 'SubZero'})
    redirected_response = response.follow()
    assert_equal(urlparse.urlsplit(response.location)[2], '/choose/zero/results')
    qs = cgi.parse_qs(urlparse.urlsplit(response.location)[3])
    assert_equal(
        qs,
        {'license-class': ['zero'],
         'name': ['ZeroMan'],
         'work_title': ['SubZero']})


def test_gpl_lgpl_chooser_redirects():
    """
    /choose/cc-gpl and /choose/cc-lgpl should now redirect to gnu.org,
    make sure that happens
    """
    gpl_redirect = TESTAPP.get('/choose/cc-gpl').location
    lgpl_redirect = TESTAPP.get('/choose/cc-lgpl').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-howto.html'
    assert gpl_redirect == lgpl_redirect == expected_redirect


def test_licenses_redirects():
    expected_redirects = (
        # GPL deed
        ('/licenses/GPL/2.0/', 'http://www.gnu.org/licenses/gpl-2.0.html'),
        # GPL deed explicit
        ('/licenses/GPL/2.0/deed', 'http://www.gnu.org/licenses/gpl-2.0.html'),
        # GPL deed with lang
        ('/licenses/GPL/2.0/deed.pt',
         'http://www.gnu.org/licenses/gpl-2.0.html'),
        # GPL RDF
        ('/licenses/GPL/2.0/rdf', 'http://www.gnu.org/licenses/gpl-2.0.rdf'),
        # LGPL deed
        ('/licenses/LGPL/2.1/', 'http://www.gnu.org/licenses/lgpl-2.1.html'),
        # LGPL deed explicit
        ('/licenses/LGPL/2.1/deed',
         'http://www.gnu.org/licenses/lgpl-2.1.html'),
        # LGPL deed with lang
        ('/licenses/LGPL/2.1/deed.pt',
         'http://www.gnu.org/licenses/lgpl-2.1.html'),
        # LGPL RDF
        ('/licenses/LGPL/2.1/rdf', 'http://www.gnu.org/licenses/lgpl-2.1.rdf'),
        # MIT redirects
        ('/licenses/MIT/',
         'http://opensource.org/licenses/mit-license.php'),
        ('/licenses/MIT/deed',
         'http://opensource.org/licenses/mit-license.php'),
        ('/licenses/MIT/deed.es',
         'http://opensource.org/licenses/mit-license.php'),
        ('/licenses/MIT/legalcode',
         'http://opensource.org/licenses/mit-license.php'),
        # BSD redirects
        ('/licenses/BSD/',
         'http://opensource.org/licenses/bsd-license.php'),
        ('/licenses/BSD/deed',
         'http://opensource.org/licenses/bsd-license.php'),
        ('/licenses/BSD/deed.es',
         'http://opensource.org/licenses/bsd-license.php'),
        ('/licenses/BSD/legalcode',
         'http://opensource.org/licenses/bsd-license.php'))

    for url, expected_redirect in expected_redirects:
        redirect = TESTAPP.get(url).location
        assert_equal(redirect, expected_redirect)


def test_normalchooser_gpl_redirects():
    """
    There was an error on the old GPL/LGPL deeds where they pointed to
    the chooser when they should have pointed to gnu.org.  As such,
    when license_code=GPL or LGPL, we should redirect.
    """
    gpl_redirect = TESTAPP.get(
        '/choose/results-one'
        '?license_code=GPL&jurisdiction=&version=2.0&lang=en').location
    lgpl_redirect = TESTAPP.get(
        '/choose/results-one'
        '?license_code=LGPL&jurisdiction=&version=2.0&lang=en').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-howto.html'
    assert gpl_redirect == lgpl_redirect == expected_redirect

    # But, no other license_code should redirect...
    assert not TESTAPP.get(
        '/choose/results-one'
        '?license_code=by&jurisdiction=&version=2.0&lang=en').location
    assert not TESTAPP.get(
        '/choose/results-one'
        '?license_code=by-sa&jurisdiction=&version=2.0&lang=en').location


def test_deeds_up_for_licenses():
    """
    Make sure all licenses that the RDF claims exist show up with 200 OK
    """
    license_uris = util.get_all_license_urls()

    for license_uri in license_uris:
        license_path = urlparse.urlsplit(license_uri)[2]
        TESTAPP.get(license_path)


class TestEmailSenderViews(unittest.TestCase):
    def setUp(self):
        util._clear_test_inboxes()
        util._clear_test_template_context()
        
    def test_work_email_send(self):
        # For doing a POST (email sending time!)
        # --------------------------------------
        response = TESTAPP.post(
            '/choose/work-email',
            {'to_email': 'recipient@example.org',
             'work_title': 'Floobie Bletch',
             'license_name': 'Scroll of Charging',
             'license_html': 'You feel charged up!'})
        
        # assert that there's 1 message in the inbox,
        # and that it's the right one
        assert_equal(len(util.EMAIL_TEST_INBOX), 1)
        sent_mail = util.EMAIL_TEST_INBOX.pop()
        assert_equal(sent_mail['To'], 'recipient@example.org')
        assert_equal(sent_mail['From'], 'info@creativecommons.org')
        assert_equal(
            sent_mail['Subject'],
            "Your Creative Commons License Information")
        mail_body = sent_mail.get_payload()

        assert 'You have selected Scroll of Charging' in mail_body
        assert 'You feel charged up!' in mail_body

        # check that the right template was loaded
        assert util.TEST_TEMPLATE_CONTEXT.has_key(
            'chooser_pages/emailhtml.html')

        # For doing a GET (shouldn't send email!)
        # ---------------------------------------
        response = TESTAPP.get(
            '/choose/work-email?license_name=Scroll+of+Charging&to_email=recipient%40example.org&work_title=Floobie+Bletch&license_html=You+feel+charged+up%21',
            expect_errors=True)
        assert_equal(response.status_int, 405)

    def test_cc0_results_email_send(self):
        util._clear_test_template_context()
        # For doing a POST (email sending time!)
        # --------------------------------------
        response = TESTAPP.post(
            '/choose/zero/results',
            {'email': 'recipient@example.org'})
        
        # assert that there's 1 message in the inbox,
        # and that it's the right one
        assert_equal(len(util.EMAIL_TEST_INBOX), 1)
        sent_mail = util.EMAIL_TEST_INBOX.pop()
        assert_equal(sent_mail['To'], 'recipient@example.org')
        assert_equal(sent_mail['From'], 'info@creativecommons.org')
        assert_equal(
            sent_mail['Subject'],
            "Your Creative Commons License Information")
        mail_body = sent_mail.get_payload()

        assert 'You have selected CC0 1.0 Universal' in mail_body
        assert 'To the extent possible under law,' in mail_body

        # check that the right template was loaded
        assert util.TEST_TEMPLATE_CONTEXT.has_key(
            'chooser_pages/zero/results.html')

        # For doing a GET (shouldn't send email!)
        # ---------------------------------------
        util._clear_test_inboxes()
        util._clear_test_template_context()

        response = TESTAPP.get(
            '/choose/zero/results?email=recipient@example.org')
        
        # assert that there's no messages in the inbox
        assert_equal(len(util.EMAIL_TEST_INBOX), 0)

        # check that the right template was loaded
        assert util.TEST_TEMPLATE_CONTEXT.has_key(
            'chooser_pages/zero/results.html')


    def test_pdmark_results_email_send(self):
        # For doing a POST (email sending time!)
        # --------------------------------------
        response = TESTAPP.post(
            '/choose/mark/results',
            {'email': 'recipient@example.org'})
        
        # assert that there's 1 message in the inbox,
        # and that it's the right one
        assert_equal(len(util.EMAIL_TEST_INBOX), 1)
        sent_mail = util.EMAIL_TEST_INBOX.pop()
        assert_equal(sent_mail['To'], 'recipient@example.org')
        assert_equal(sent_mail['From'], 'info@creativecommons.org')
        assert_equal(
            sent_mail['Subject'],
            "Your Creative Commons License Information")
        mail_body = sent_mail.get_payload()

        assert 'You have selected Public Domain Mark 1.0' in mail_body
        assert 'free of known copyright restrictions' in mail_body

        # check that the right template was loaded
        assert util.TEST_TEMPLATE_CONTEXT.has_key(
            'chooser_pages/pdmark/results.html')

        # For doing a GET (shouldn't send email!)
        # ---------------------------------------
        util._clear_test_inboxes()
        util._clear_test_template_context()

        response = TESTAPP.get(
            '/choose/mark/results?email=recipient@example.org')
        
        # assert that there's no messages in the inbox
        assert_equal(len(util.EMAIL_TEST_INBOX), 0)

        # check that the right template was loaded
        assert util.TEST_TEMPLATE_CONTEXT.has_key(
            'chooser_pages/pdmark/results.html')


def test_publicdomain_direct_redirect():
    """
    Test to ensure that /choose/publicdomain-direct redirects
    appropriately
    """
    response = TESTAPP.get(
        '/choose/publicdomain-direct?'
        'stylesheet=foo.css&partner=blah')
    redirected_response = response.follow()
    assert_equal(
        urlparse.urlsplit(response.location)[2],
        '/choose/zero/partner')
    qs = cgi.parse_qs(urlparse.urlsplit(response.location)[3])
    assert_equal(
        qs,
        {'stylesheet': ['foo.css'],
         'partner': ['blah']})


def test_publicdomain_partners_alternatelinks():
    """
    Make sure the publicdomain partner pages (both PDM and CC0) have
    working links to other partner pages.  CC0 should link to PDM and
    vice versa, and the query parameters should be preserved.
    """
    expected_response_qs = {
        'lang': ['en'],
        'partner': ['http://nethack.org/'],
        'exit_url': ['http://nethack.org/return_from_cc?license_url=[license_url]&license_name=[license_name]'],
        'stylesheet': ['http://nethack.org/yendor.css']}

    # Test for PDM's CC0 link
    response = TESTAPP.get(
        '/choose/mark/partner?'
        'lang=en&partner=http://nethack.org/&'
        'exit_url=http://nethack.org/return_from_cc?license_url=[license_url]%26license_name=[license_name]&'
        'stylesheet=http://nethack.org/yendor.css&'
        'extraneous_argument=large%20mimic')
    
    response_etree = lxml_html.parse(StringIO.StringIO(response.unicode_body))
    other_pd_href = response_etree.xpath(
        '//a[text()="CC0 public domain dedication"]')[0].attrib['href']
    assert_equal(urlparse.urlsplit(other_pd_href)[2], '/choose/zero/partner')
    qs = cgi.parse_qs(urlparse.urlsplit(other_pd_href)[3])
    assert_equal(qs, expected_response_qs)

    # Test for CC0's PDM link
    response = TESTAPP.get(
        '/choose/zero/partner?'
        'lang=en&partner=http://nethack.org/&'
        'exit_url=http://nethack.org/return_from_cc?license_url=[license_url]%26license_name=[license_name]&'
        'stylesheet=http://nethack.org/yendor.css&'
        'extraneous_argument=large%20mimic')
    
    response_etree = lxml_html.parse(StringIO.StringIO(response.unicode_body))
    other_pd_href = response_etree.xpath(
        '//a[text()="Public Domain Mark"]')[0].attrib['href']
    assert_equal(urlparse.urlsplit(other_pd_href)[2], '/choose/mark/partner')
    qs = cgi.parse_qs(urlparse.urlsplit(other_pd_href)[3])
    assert_equal(qs, expected_response_qs)


def test_publicdomain_partners_exiturls():
    """
    Ensure that the exit urls from publicdomain partner pages make
    sense.
    """
    # PDM's exit URL
    response = TESTAPP.get(
        '/choose/mark/partner?'
        'lang=en&partner=http://nethack.org/&'
        'exit_url=http://nethack.org/return_from_cc?license_url=[license_url]%26license_name=[license_name]&'
        'stylesheet=http://nethack.org/yendor.css&'
        'extraneous_argument=large%20mimic')
    
    response_etree = lxml_html.parse(StringIO.StringIO(response.unicode_body))
    proceed_href = response_etree.xpath(
        '//a[text()="proceed"]')[0].attrib['href']
    assert_equal(
        proceed_href,
        ('http://nethack.org/return_from_cc?'
         'license_url=http%3A//creativecommons.org/publicdomain/mark/1.0/&'
         'license_name=Public%20Domain%20Mark%201.0'))
    
    # CC0's exit URL
    response = TESTAPP.get(
        '/choose/zero/partner?'
        'lang=en&partner=http://nethack.org/&'
        'exit_url=http://nethack.org/return_from_cc?license_url=[license_url]%26license_name=[license_name]&'
        'stylesheet=http://nethack.org/yendor.css&'
        'extraneous_argument=large%20mimic')

    response_etree = lxml_html.parse(StringIO.StringIO(response.unicode_body))
    proceed_href = response_etree.xpath(
        '//a[text()="proceed"]')[0].attrib['href']
    assert_equal(
        proceed_href,
        ('http://nethack.org/return_from_cc?'
         'license_url=http%3A//creativecommons.org/publicdomain/zero/1.0/&'
         'license_name=CC0%201.0%20Universal'))


def test_deed_fallbacks():
    """
    Test that we fallback appropriately when a deed gets a locale
    that's unknown (or deprecated, which is the same thing via a
    special case)
    """
    def _redirects_expectedly(source_url, redirect_url):
        response = TESTAPP.get(source_url)
        redirected_response = response.follow()
        result_url = urlparse.urlsplit(response.location)[2]
        assert_equal(result_url, redirect_url)

    # Redirects for totally absurd language
    _redirects_expectedly(
        '/licenses/by/3.0/deed.MONKEYS',
        '/licenses/by/3.0/deed.en')

    # Redirects for a language with an absurd/no-longer-existing
    # country component
    _redirects_expectedly(
        '/licenses/by/3.0/deed.pt_LARGEMIMIC',
        '/licenses/by/3.0/deed.pt')
        
    # Don't redirect when the language is valid
    assert_equal(TESTAPP.get('/licenses/by/3.0/deed.pt').location, None)

    # Don't redirect when no language is specified
    assert_equal(TESTAPP.get('/licenses/by/3.0/deed').location, None)
    assert_equal(TESTAPP.get('/licenses/by/3.0/').location, None)


USE_LICENSE_TEXT = 'Use this license for your own work.'

def test_retired_deeds():
    """
    We shouldn't indicate how to use a license for your own works when
    the license is retired.
    """
    # Don't tell users how to use retired licenses!
    assert (
        USE_LICENSE_TEXT
        not in TESTAPP.get('/licenses/sampling/1.0/').unicode_body)

    # We should have that text on non-retired licenses though :)
    assert USE_LICENSE_TEXT in TESTAPP.get('/licenses/by/3.0/').unicode_body


def test_choose_retired_redirects():
    """
    If a user somehow 'chooses' a retired license, it should redirect
    to /retiredlicenses
    """
    response = TESTAPP.get(
        '/choose/results-one?'
        'license_code=devnations&jurisdiction=&version=2.0&lang=en')
    retired_redirect = urlparse.urlsplit(response.location)[2]
    expected_redirect = '/retiredlicenses'
    assert_equal(retired_redirect, expected_redirect)

    # Special case: PDCC redirects to /publicdomain/
    response = TESTAPP.get(
        '/choose/results-one?'
        'license_code=publicdomain')
    retired_redirect = urlparse.urlsplit(response.location)[2]
    expected_redirect = '/publicdomain/'
    assert_equal(retired_redirect, expected_redirect)

    # But, obviously don't redirect when we have non-deprecated licenses :)
    response = TESTAPP.get('/choose/results-one').location == None


def test_chooser_gives_correct_licenses():
    """
    Test that the chooser gives us the right licenses.

    Some of these may need to be updated as we release new version numbers!
    """
    
    def _check_license_url_against_parameters(parameters, expected_url):
        """
        See if the license's url given by the chooser for PARAMETERS
        matches EXPECTED_URL
        """
        util._clear_test_template_context()
        TESTAPP.get(
            '/choose/results-one?' +
            urllib.urlencode(parameters))
        license = util.TEST_TEMPLATE_CONTEXT[
            'chooser_pages/results.html']['license']
        assert_equal(license.uri, expected_url)
            
    ###################
    ### Boring default!
    ###################

    # default is CC BY 3.0.  Make sure it is!
    _check_license_url_against_parameters(
        {}, 'http://creativecommons.org/licenses/by/3.0/')

    ###################
    ### By license code
    ###################

    _check_license_url_against_parameters(
        {'license_code': 'by'},
        'http://creativecommons.org/licenses/by/3.0/')
    _check_license_url_against_parameters(
        {'license_code': 'by-sa'},
        'http://creativecommons.org/licenses/by-sa/3.0/')
    _check_license_url_against_parameters(
        {'license_code': 'by-sa',
         'version': '2.0'},
        'http://creativecommons.org/licenses/by-sa/2.0/')
    _check_license_url_against_parameters(
        {'license_code': 'by-sa',
         'version': '2.0'},
        'http://creativecommons.org/licenses/by-sa/2.0/')
    _check_license_url_against_parameters(
        {'license_code': 'by-sa',
         'version': '2.0'},
        'http://creativecommons.org/licenses/by-sa/2.0/')
    _check_license_url_against_parameters(
        {'license_code': 'by-nc-sa',
         'version': '2.0',
         'jurisdiction': 'at'},
        'http://creativecommons.org/licenses/by-nc-sa/2.0/at/')

    ##################
    ### By license url
    ##################
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by/3.0/'},
        'http://creativecommons.org/licenses/by/3.0/')
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by-nc/3.0/'},
        'http://creativecommons.org/licenses/by-nc/3.0/')
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by-nd/3.0/'},
        'http://creativecommons.org/licenses/by-nd/3.0/')
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by-sa/3.0/'},
        'http://creativecommons.org/licenses/by-sa/3.0/')
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by-nc-nd/3.0/'},
        'http://creativecommons.org/licenses/by-nc-nd/3.0/')
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by-nc-sa/3.0/'},
        'http://creativecommons.org/licenses/by-nc-sa/3.0/')
    _check_license_url_against_parameters(
        {'license_url': 'http://creativecommons.org/licenses/by-nc-sa/3.0/pl/'},
        'http://creativecommons.org/licenses/by-nc-sa/3.0/pl/')

    ################
    ### By "answers"
    ################
    _check_license_url_against_parameters(
        {'field_commercial': 'y',
         'field_derivatives': 'y'},
        'http://creativecommons.org/licenses/by/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'n',
         'field_derivatives': 'y'},
        'http://creativecommons.org/licenses/by-nc/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'y',
         'field_derivatives': 'n'},
        'http://creativecommons.org/licenses/by-nd/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'y',
         'field_derivatives': 'sa'},
        'http://creativecommons.org/licenses/by-sa/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'n',
         'field_derivatives': 'n'},
        'http://creativecommons.org/licenses/by-nc-nd/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'n',
         'field_derivatives': 'sa'},
        'http://creativecommons.org/licenses/by-nc-sa/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'n',
         'field_derivatives': 'sa',
         'jurisdiction': 'pl'},
        'http://creativecommons.org/licenses/by-nc-sa/3.0/pl/')
    _check_license_url_against_parameters(
        {'field_commercial': 'n',
         'field_derivatives': 'sa',
         'field_jurisdiction': 'pl'},
        'http://creativecommons.org/licenses/by-nc-sa/3.0/pl/')

    # Also, we used to use "yes" instead of "y", make sure that still
    # means "y"
    _check_license_url_against_parameters(
        {'field_commercial': 'yes',
         'field_derivatives': 'yes'},
        'http://creativecommons.org/licenses/by/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'n',
         'field_derivatives': 'yes'},
        'http://creativecommons.org/licenses/by-nc/3.0/')
    _check_license_url_against_parameters(
        {'field_commercial': 'yes',
         'field_derivatives': 'n'},
        'http://creativecommons.org/licenses/by-nd/3.0/')


def test_license_catcher():
    """
    Test that the "license catcher" suggests the right licenses
    """
    def get_license_links(response):
        response_tree = lxml_html.parse(
            StringIO.StringIO(response.unicode_body))
        return [
            el.attrib['href']
            for el in response_tree.xpath("id('suggested_licenses')//a")]

    assert_equal(
        get_license_links(TESTAPP.get('/licenses/by/', status=404)),
        ['http://creativecommons.org/licenses/by/3.0/',
         'http://creativecommons.org/licenses/by/2.5/',
         'http://creativecommons.org/licenses/by/2.0/',
         'http://creativecommons.org/licenses/by/1.0/'])

    assert_equal(
        get_license_links(TESTAPP.get('/licenses/by-sa/', status=404)),
        ['http://creativecommons.org/licenses/by-sa/3.0/',
         'http://creativecommons.org/licenses/by-sa/2.5/',
         'http://creativecommons.org/licenses/by-sa/2.0/',
         'http://creativecommons.org/licenses/by-sa/1.0/'])

    assert_equal(
        get_license_links(TESTAPP.get('/publicdomain/mark/', status=404)),
        ['http://creativecommons.org/publicdomain/mark/1.0/'])

    assert_equal(
        get_license_links(TESTAPP.get('/publicdomain/zero/', status=404)),
        ['http://creativecommons.org/publicdomain/zero/1.0/'])


def test_deed_w3_validation():
    """
    Tests to see if the deeds pass the w3c validator.
    """

    paths = [
        "/licenses/by/3.0/",
        "/licenses/by-sa/3.0/",
        "/licenses/by-nc/3.0/",
        "/licenses/by-nc-sa/3.0/",
        "/licenses/by-nc-nd/3.0/",
        "/licenses/by-nd/3.0/",
        "/publicdomain/zero/1.0/",
        "/publicdomain/mark/1.0/",
        ]
    temp_dir = tempfile.mkdtemp()
    failures = []

    try:
        print "\n"
        for path in paths:
            view_result = TESTAPP.get(path).text.encode('utf-8')
            temp_path = os.path.join(temp_dir, "validate_me.html")
            storage = file(temp_path, mode="w+b")
            storage.write(view_result)
            storage.close()

            register_openers()
            data, headers = multipart_encode({
                    "uploaded_file" : open(temp_path),
                    "charset" : "(detect automatically)",
                    "doctype" : "Inline",
                    "group" : 0,
                    })
            req = urllib2.Request("http://validator.w3.org/check", 
                                  data, headers)
            try:
                raw = urllib2.urlopen(req).read()
            except urllib2.HTTPError:
                print "(proxy error... waiting 30 seconds before retry...)"
                import time; time.sleep(30)
                raw = urllib2.urlopen(req).read()
            html = lxml_html.fromstring(raw)
            result = html.get_element_by_id("result")
            if result.findall("h3")[0].text == "Congratulations":
                print "\n==>", path, "passes the w3c validator :D\n"
                continue
            else:
                print "\n==>", path, "fails the w3c validator:\n"
                errors = html.get_element_by_id("error_loop").findall("*")
                error_count = len(errors)
                failures.append((path, error_count))
                for error in errors:
                    text = [i.text for i in error.findall("*") if i.text]
                    info = map(str.strip, text.pop(0).split("\n"))
                    info = "".join(info).split(",")
                    info.append("".join(text).strip())
                    print " {0} {1}\n   {2}\n".format(*info)
    except:
        # clean up tempfiles before raising an error
        shutil.rmtree(temp_dir)
        raise

    assert not failures
