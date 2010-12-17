import cgi
import pkg_resources
import urlparse
import unittest
from lxml import html as lxml_html
import StringIO

try:
    import json
except ImportError:
    import simplejson as json

import webtest
from webob import Request
import RDF

from cc.engine import app, staticdirect, util, views
from cc.engine.licenses import views as license_views
import cc.license
from cc.license._lib import rdf_helper

util._activate_testing()


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


def test_root_view():
    response = TESTAPP.get('/')
    assert response.body == 'This is the root'


def test_licenses_view():
    response = TESTAPP.get('/licenses/')
    assert '<h1>Creative Commons Licenses</h1>' in response.body


## Deed view tests


def _deed_tester(url, template_path,
                 expected_code, expected_version, expected_jurisdiction,
                 expected_license):
    response = TESTAPP.get(url)
    namespace = util.ZPT_TEST_TEMPLATES.pop(
            util.full_zpt_filename(template_path))
    request = namespace['request']
    assert namespace['license'] == expected_license
    assert request.matchdict.get('code') == expected_code
    assert request.matchdict.get('version') == expected_version
    assert request.matchdict.get('jurisdiction') == expected_jurisdiction


def test_standard_deeds_licenses():
    """
    Make sure the correct licenses get selected from the deeds
    """
    _deed_tester(
        '/licenses/by/3.0/', 'licenses/standard_deed.pt',
        'by', '3.0', None,
        cc.license.by_code('by', '3.0'))
    _deed_tester(
        '/licenses/by-sa/3.0/', 'licenses/standard_deed.pt',
        'by-sa', '3.0', None,
        cc.license.by_code('by-sa'))

    # MIT and BSD, the only ones which are called without version
    # codes in the URL
    _deed_tester(
        '/licenses/MIT/', 'licenses/mitbsd_deed.pt',
        'MIT', None, None,
        cc.license.by_code('MIT'))
    _deed_tester(
        '/licenses/BSD/', 'licenses/mitbsd_deed.pt',
        'BSD', None, None,
        cc.license.by_code('BSD'))


## RDF view tests
RDF_HEADER = 'application/rdf+xml; charset=UTF-8'

def _rdf_tester(url, rdf_file):
    response = TESTAPP.get(url)
    rdf_file_contents = util.unicode_cleaner(
        file(pkg_resources.resource_filename(
                'cc.licenserdf', rdf_file)).read())
    assert response.headers['Content-Type'] == RDF_HEADER
    assert response.unicode_body == rdf_file_contents

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
                assert string_test in view_result.unicode_body


def test_license_to_choose_redirect():
    # Make sure we redirect from /license/* to /choose/ and keep the
    # GET parameters
    response = TESTAPP.get(
        '/license/zero/results?'
        'license-class=zero&name=ZeroMan&work_title=SubZero')
    redirected_response = response.follow()
    assert urlparse.urlsplit(response.location)[2] == '/choose/zero/results'
    qs = cgi.parse_qs(urlparse.urlsplit(response.location)[3])
    assert qs == {
        'license-class': ['zero'],
        'name': ['ZeroMan'],
        'work_title': ['SubZero']}

    # Also make sure that POST redirects work
    response = TESTAPP.post(
        '/license/zero/results',
        {'license-class': 'zero',
         'name': 'ZeroMan',
         'work_title': 'SubZero'})
    redirected_response = response.follow()
    assert urlparse.urlsplit(response.location)[2] == '/choose/zero/results'
    qs = cgi.parse_qs(urlparse.urlsplit(response.location)[3])
    assert qs == {
        'license-class': ['zero'],
        'name': ['ZeroMan'],
        'work_title': ['SubZero']}


def test_gpl_lgpl_chooser_redirects():
    """
    /choose/cc-gpl and /choose/cc-lgpl should now redirect to gnu.org,
    make sure that happens
    """
    gpl_redirect = TESTAPP.get('/choose/cc-gpl').location
    lgpl_redirect = TESTAPP.get('/choose/cc-lgpl').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-howto.html'
    assert gpl_redirect == lgpl_redirect == expected_redirect


def test_gpl_lgpl_deed_and_rdf_redirects():
    """
    Make sure appropriate /licenses/ pages for GPL and LGPL redirect.
    """
    # GPL deed
    redirect = TESTAPP.get('/licenses/GPL/2.0/').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-2.0.html'
    assert redirect == expected_redirect

    # GPL deed explicit
    redirect = TESTAPP.get('/licenses/GPL/2.0/deed').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-2.0.html'
    assert redirect == expected_redirect

    # GPL deed with lang
    redirect = TESTAPP.get('/licenses/GPL/2.0/deed.pt').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-2.0.html'
    assert redirect == expected_redirect

    # GPL RDF
    redirect = TESTAPP.get('/licenses/GPL/2.0/rdf').location
    expected_redirect = 'http://www.gnu.org/licenses/gpl-2.0.rdf'
    assert redirect == expected_redirect

    # LGPL deed
    redirect = TESTAPP.get('/licenses/LGPL/2.1/').location
    expected_redirect = 'http://www.gnu.org/licenses/lgpl-2.1.html'
    assert redirect == expected_redirect

    # LGPL deed explicit
    redirect = TESTAPP.get('/licenses/LGPL/2.1/deed').location
    expected_redirect = 'http://www.gnu.org/licenses/lgpl-2.1.html'
    assert redirect == expected_redirect

    # LGPL deed with lang
    redirect = TESTAPP.get('/licenses/LGPL/2.1/deed.pt').location
    expected_redirect = 'http://www.gnu.org/licenses/lgpl-2.1.html'
    assert redirect == expected_redirect

    # LGPL RDF
    redirect = TESTAPP.get('/licenses/LGPL/2.1/rdf').location
    expected_redirect = 'http://www.gnu.org/licenses/lgpl-2.1.rdf'
    assert redirect == expected_redirect


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
        util._clear_zpt_test_templates()
        
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
        assert len(util.EMAIL_TEST_INBOX) == 1
        sent_mail = util.EMAIL_TEST_INBOX.pop()
        assert sent_mail['To'] == 'recipient@example.org'
        assert sent_mail['From'] == 'info@creativecommons.org'
        assert sent_mail['Subject'] == \
            "Your Creative Commons License Information"
        mail_body = sent_mail.get_payload()

        assert 'You have selected Scroll of Charging' in mail_body
        assert 'You feel charged up!' in mail_body

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/emailhtml.pt'))

        # For doing a GET (shouldn't send email!)
        # ---------------------------------------
        response = TESTAPP.get(
            '/choose/work-email?license_name=Scroll+of+Charging&to_email=recipient%40example.org&work_title=Floobie+Bletch&license_html=You+feel+charged+up%21',
            expect_errors=True)
        assert response.status_int == 405

    def test_cc0_results_email_send(self):
        # For doing a POST (email sending time!)
        # --------------------------------------
        response = TESTAPP.post(
            '/choose/zero/results',
            {'email': 'recipient@example.org'})
        
        # assert that there's 1 message in the inbox,
        # and that it's the right one
        assert len(util.EMAIL_TEST_INBOX) == 1
        sent_mail = util.EMAIL_TEST_INBOX.pop()
        assert sent_mail['To'] == 'recipient@example.org'
        assert sent_mail['From'] == 'info@creativecommons.org'
        assert sent_mail['Subject'] == \
            "Your Creative Commons License Information"
        mail_body = sent_mail.get_payload()

        assert 'You have selected CC0 1.0 Universal' in mail_body
        assert 'To the extent possible under law,' in mail_body

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/zero/results.pt'))

        # For doing a GET (shouldn't send email!)
        # ---------------------------------------
        util._clear_test_inboxes()
        util._clear_zpt_test_templates()

        response = TESTAPP.get(
            '/choose/zero/results?email=recipient@example.org')
        
        # assert that there's no messages in the inbox
        assert len(util.EMAIL_TEST_INBOX) == 0

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/zero/results.pt'))


    def test_pdmark_results_email_send(self):
        # For doing a POST (email sending time!)
        # --------------------------------------
        response = TESTAPP.post(
            '/choose/mark/results',
            {'email': 'recipient@example.org'})
        
        # assert that there's 1 message in the inbox,
        # and that it's the right one
        assert len(util.EMAIL_TEST_INBOX) == 1
        sent_mail = util.EMAIL_TEST_INBOX.pop()
        assert sent_mail['To'] == 'recipient@example.org'
        assert sent_mail['From'] == 'info@creativecommons.org'
        assert sent_mail['Subject'] == \
            "Your Creative Commons License Information"
        mail_body = sent_mail.get_payload()

        assert 'You have selected Public Domain Mark 1.0' in mail_body
        assert 'free of known copyright restrictions' in mail_body

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/pdmark/results.pt'))

        # For doing a GET (shouldn't send email!)
        # ---------------------------------------
        util._clear_test_inboxes()
        util._clear_zpt_test_templates()

        response = TESTAPP.get(
            '/choose/mark/results?email=recipient@example.org')
        
        # assert that there's no messages in the inbox
        assert len(util.EMAIL_TEST_INBOX) == 0

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/pdmark/results.pt'))


def test_publicdomain_direct_redirect():
    """
    Test to ensure that /choose/publicdomain-direct redirects
    appropriately
    """
    response = TESTAPP.get(
        '/choose/publicdomain-direct?'
        'stylesheet=foo.css&partner=blah')
    redirected_response = response.follow()
    assert urlparse.urlsplit(response.location)[2] == '/choose/zero/partner'
    qs = cgi.parse_qs(urlparse.urlsplit(response.location)[3])
    assert qs == {
        'stylesheet': ['foo.css'],
        'partner': ['blah']}


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
    assert urlparse.urlsplit(other_pd_href)[2] == '/choose/zero/partner'
    qs = cgi.parse_qs(urlparse.urlsplit(other_pd_href)[3])
    assert qs == expected_response_qs

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
    assert urlparse.urlsplit(other_pd_href)[2] == '/choose/mark/partner'
    qs = cgi.parse_qs(urlparse.urlsplit(other_pd_href)[3])
    assert qs == expected_response_qs


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
    assert proceed_href == (
        'http://nethack.org/return_from_cc?'
        'license_url=http%3A//creativecommons.org/publicdomain/mark/1.0/&'
        'license_name=Public%20Domain%20Mark%201.0')
    
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
    assert proceed_href == (
        'http://nethack.org/return_from_cc?'
        'license_url=http%3A//creativecommons.org/publicdomain/zero/1.0/&'
        'license_name=CC0%201.0%20Universal')


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
        assert result_url == redirect_url

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
    assert TESTAPP.get('/licenses/by/3.0/deed.pt').location == None

    # Don't redirect when no language is specified
    assert TESTAPP.get('/licenses/by/3.0/deed').location == None
    assert TESTAPP.get('/licenses/by/3.0/').location == None
