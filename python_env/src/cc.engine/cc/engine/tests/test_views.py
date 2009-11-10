import lxml

from webob import Request

from cc.engine import util
from cc.engine import views


util._activate_zpt_testing()


### ---------------
### routing testing
### ---------------


### ------------
### view testing
### ------------

def test_root_view():
    response = views.root_view(Request.blank('/'))
    assert response.unicode_body == 'This is the root'


def test_licenses_view():
    request = Request('/licenses/')
    response = views.licenses_view(request)
    namespace = util.ZPT_TEST_TEMPLATES.pop(
        util.full_zpt_filename('catalog_pages/licenses-index.pt'))
    namespace['request'] == request


def test_license_deed_view():
    pass
