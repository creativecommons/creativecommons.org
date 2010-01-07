import pkg_resources
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
        staticdirect.RemoteStaticDirect('/static/')))


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
