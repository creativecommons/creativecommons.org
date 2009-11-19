from webob import Response

from cc.engine import util
from cc.i18npkg import ccorg_i18n_setup


def chooser_view(request):


    return util.plain_template_view('chooser_pages/index.pt', request)
