import pkg_resources
import lxml

from webob import Request

from cc.engine.app import load_controller
from cc.engine import util
from cc.engine import views
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
    response = views.licenses_view(request)
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


class BaseTestLicenseRdfView(BaseViewTests):
    def _read_rdf_file_contents(self):
        return file(self.rdf_file).read()

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
        'controller': 'cc.engine.views:license_rdf_view'}
    rdf_file = pkg_resources.resource_filename(
        'cc.licenserdf', 'licenses/creativecommons.org_licenses_by-sa_2.0_.rdf')


def test_license_deeds():
    pass
