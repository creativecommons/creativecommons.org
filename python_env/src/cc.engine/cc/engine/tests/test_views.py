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


def test_standard_deeds():
    response = TESTAPP.get('/licenses/by/3.0/')
    namespace = util.ZPT_TEST_TEMPLATES.pop(
            util.full_zpt_filename('licenses/standard_templates/deed.pt'))
    assert namespace['license'] == cc.license.by_code('by', '3.0')
    request = namespace['request']
    assert request.matchdict['code'] == 'by'
    assert request.matchdict['version'] == '3.0'


#####
### BSD/MIT license checks: We know these fail currently :(
#####

class FakeBSDLicense(object): pass
class FakeMITLicense(object): pass


class TestBSDView(object):
    url = '/licenses/BSD/'
    matchdict = {
        # Code??? version???
        'controller': 'cc.engine.licenses.views:license_deed_view'}
    expected_namespace = {
        'license': FakeBSDLicense()}

    def test_title(self):
        # This test totally sucks
        'BSD License' in self.unicode_body


class TestMITView(object):
    url = '/licenses/MIT/'
    matchdict = {
        # Code??? version???
        'controller': 'cc.engine.licenses.views:license_deed_view'}
    expected_namespace = {
        'license': FakeMITLicense()}

    def test_title(self):
        # This test totally sucks
        'MIT License' in self.unicode_body


## RDF view tests

class BaseTestLicenseRdfView(object):
    def _read_rdf_file_contents(self):
        return file(
            pkg_resources.resource_filename(
                'cc.licenserdf', self.rdf_file)).read()

    def test_has_rdf(self):
        rdf_contents = util.unicode_cleaner(self._read_rdf_file_contents())
        assert rdf_contents == self.response.unicode_body
        
    def test_headers(self):
        expected_header = 'application/rdf+xml; charset=UTF-8'
        assert self.response.headers['Content-Type'] == expected_header


class TestBySaRDFView(object):
    url = '/licenses/by-sa/2.0/rdf'
    matchdict = {
        'code': 'by-sa',
        'version': '2.0',
        'controller': 'cc.engine.licenses.views:license_rdf_view'}
    rdf_file = 'licenses/creativecommons.org_licenses_by-sa_2.0_.rdf'


def test_license_deeds():
    pass
