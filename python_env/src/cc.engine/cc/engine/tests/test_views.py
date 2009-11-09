import lxml

from webob import Request

from cc.engine import util
from cc.engine import views


util._activate_zpt_testing()


def test_root_view():
    response = views.root_view(Request.blank('/'))
    assert response.unicode_body == 'This is the root'

