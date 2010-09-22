import cgi
import pkg_resources
import urlparse
import unittest
import lxml
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


def test_deeds_up_for_licenses():
    """
    Make sure all licenses that the RDF claims exist show up with 200 OK
    """
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
    license_uris = tuple( str(s['luri'].uri) for s in solns )

    for license_uri in license_uris:
        license_path = urlparse.urlsplit(license_uri)[2]
        TESTAPP.get(license_path)


class TestEmailSenderViews(unittest.TestCase):
    def setUp(self):
        util._clear_test_inboxes()
        util._clear_zpt_test_templates()
        
    def test_work_email_send(self):
        pass

    def test_cc0_results_email_send(self):
        # For doing a POST (email sending time!)
        # --------------------------------------
        results = TESTAPP.post(
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

        # For doing a GET (shouldn't send email!
        # --------------------------------------
        util._clear_test_inboxes()
        util._clear_zpt_test_templates()

        results = TESTAPP.get(
            '/choose/zero/results?email=recipient@example.org')
        
        # assert that there's no messages in the inbox
        assert len(util.EMAIL_TEST_INBOX) == 0

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/zero/results.pt'))


    def test_pdmark_results_email_send(self):
        # For doing a POST (email sending time!)
        # --------------------------------------
        results = TESTAPP.post(
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
        assert 'free of copyright restrictions' in mail_body

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/pdmark/results.pt'))

        # For doing a GET (shouldn't send email!
        # --------------------------------------
        util._clear_test_inboxes()
        util._clear_zpt_test_templates()

        results = TESTAPP.get(
            '/choose/mark/results?email=recipient@example.org')
        
        # assert that there's no messages in the inbox
        assert len(util.EMAIL_TEST_INBOX) == 0

        # check that the right template was loaded
        assert util.ZPT_TEST_TEMPLATES.has_key(
            util.full_zpt_filename('chooser_pages/pdmark/results.pt'))
