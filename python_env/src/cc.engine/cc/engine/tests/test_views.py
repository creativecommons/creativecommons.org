import pkg_resources
import lxml

from webob import Request

from cc.engine.app import load_controller
from cc.engine import util
from cc.engine import views
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

def test_root_view():
    response = views.root_view(Request.blank('/'))
    assert response.unicode_body == 'This is the root'


def test_licenses_view():
    request = Request.blank('/licenses/')
    response = license_views.licenses_view(request)
    namespace = util.ZPT_TEST_TEMPLATES.pop(
        util.full_zpt_filename('catalog_pages/licenses-index.pt'))
    namespace['request'] == request


def _lc_tester():
    pass


#    expected_license = cc.license.by_code(


### Basic view testing class

class BaseViewTests(object):
    def setUp(self):
        self.request = Request.blank(self.url)
        self.request.matchdict = self.matchdict
        self.controller = load_controller(self.matchdict['controller'])
        self.response = self.controller(self.request)


## Deed view tests

class BaseDeedView(BaseViewTests):
    def test_template_namespace_matching(self):
        """
        Make sure that the elements in our expected_namespace match
        the elements in the actual namespace passed in.

        As a side effect, this also checks that the template used is
        the one we expected :)
        """
        namespace = util.ZPT_TEST_TEMPLATES.pop(
            util.full_zpt_filename('licenses/standard_templates/deed.pt'))
        for key, value in self.expected_namespace.iteritems():
            assert namespace[key] == value


class TestByNdDeedThreeOhView(BaseDeedView):
    url = '/licenses/by-nd/3.0/'
    matchdict = {
        'code': 'by-nd',
        'version': '3.0',
        'controller': 'cc.engine.licenses.views:license_deed_view'}
    expected_namespace = {
        'license': cc.license.by_code('by-nd', '3.0'),
        }


#####
### BSD/MIT license checks: We know these fail currently :(
#####

class FakeBSDLicense(object): pass
class FakeMITLicense(object): pass

class TestBSDView(BaseDeedView):
    url = '/licenses/BSD/'
    matchdict = {
        # Code??? version???
        'controller': 'cc.engine.licenses.views:license_deed_view'}
    expected_namespace = {
        'license': FakeBSDLicense()}

    def test_title(self):
        # This test totally sucks
        'BSD License' in self.unicode_body


class TestMITView(BaseDeedView):
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

class BaseTestLicenseRdfView(BaseViewTests):
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


class TestBySaRDFView(BaseTestLicenseRdfView):
    url = '/licenses/by-sa/2.0/rdf'
    matchdict = {
        'code': 'by-sa',
        'version': '2.0',
        'controller': 'cc.engine.licenses.views:license_rdf_view'}
    rdf_file = 'licenses/creativecommons.org_licenses_by-sa_2.0_.rdf'




def test_license_deeds():
    pass
