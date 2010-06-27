import cgi
import pkg_resources
import urlparse
import lxml

import webtest
from webob import Request

from cc.engine import app, staticdirect, util, views
from cc.engine.licenses import views as license_views
import cc.license

util._activate_zpt_testing()


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


ALL_VIEWS_LIST = [
    '/publicdomain/',
    '/licenses/',

    # license deeds
    '/licenses/by/3.0/', '/licenses/by/3.0/deed', '/licenses/by/3.0/deed.es',
    '/licenses/by/3.0/rdf', '/licenses/by/3.0/legalcode',
    '/licenses/by/3.0/legalcode-plain',

    # jurisdiction license deeds
    '/licenses/by/3.0/us/', '/licenses/by/3.0/us/deed',
    '/licenses/by/3.0/us/deed.es', '/licenses/by/3.0/us/rdf',
    '/licenses/by/3.0/us/legalcode', '/licenses/by/3.0/us/legalcode-plain',

    # MIT / BSD
    '/licenses/MIT/', '/licenses/BSD/',
    '/licenses/MIT/rdf', '/licenses/BSD/rdf',
    # these should redirect..
    '/licenses/MIT/legalcode', '/licenses/BSD/legalcode',

    # Publicdomain
    '/licenses/publicdomain/', '/licenses/publicdomain/deed',
    '/licenses/publicdomain/deed.ru', '/licenses/publicdomain/rdf',

    # CC0
    '/publicdomain/zero/1.0/', '/publicdomain/zero/1.0/deed',
    '/publicdomain/zero/1.0/deed.es', '/publicdomain/zero/1.0/legalcode',
    '/publicdomain/zero/1.0/legalcode-plain',

    # CC license chooser
    '/choose/', '/choose/results-one', '/choose/xmp',
    '/choose/get-html', '/choose/get-rdf', '/choose/wiki',
    '/choose/non-web-popup', '/choose/work-html-popup',
    ### We should test this one with a "real" unit test.
    ##'/choose/work-email'

    # FSF choosers
    '/choose/cc-gpl', '/choose/cc-lgpl',

    # Public domain chooser
    '/choose/publicdomain-2', '/choose/publicdomain-3',
    '/choose/publicdomain-4',
    '/choose/publicdomain-4?understand=confirm&field1=continue',
    '/choose/publicdomain-4?understand=confirm&field1=continue&title=&foocopyright_holder=bar',
    '/choose/publicdomain-direct',

    # CC0 chooser
    '/choose/zero/', '/choose/zero/waiver', '/choose/zero/confirm',
    '/choose/zero/results',
    '/choose/zero/confirm?license-class=zero&name=&actor_href=&work_title=&work_jurisdiction=-&confirm=confirm&understand=confirm&field1=continue',
    '/choose/zero/results?license-class=zero&name=&actor_href=&work_title=&work_jurisdiction=-&confirm=confirm&understand=confirm&field1=continue&waiver-affirm=affirm',
    '/choose/zero/confirm?license-class=zero&name=foo&actor_href=bar&work_title=baz&work_jurisdiction=BA&confirm=confirm&understand=confirm&field1=continue',
    '/choose/zero/results?license-class=zero&name=foo&actor_href=bar&work_title=baz&work_jurisdiction=BA&confirm=confirm&understand=confirm&field1=continue&waiver-affirm=affirm',
    '/choose/zero/partner',

    # Characteristics
    '/characteristic/by', '/characteristic/nc', '/characteristic/nd',
    '/characteristic/sa']
    

def test_all_views_up_simple():
    """
    Super simple test to make sure all GET'able views are up & return
    200 OK or redirect
    """
    for view in ALL_VIEWS_LIST:
        TESTAPP.get(view)


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
