import mimetypes
import urllib

from webob import Response, exc

from cc.engine import util


def root_view(request):
    return Response("This is the root")


def work_html_redirect(request):
    new_url = '/choose/work-html-popup'
    if request.GET:
        new_url = '%s?%s' % (
            new_url, urllib.urlencode(request.GET))
    return exc.HTTPTemporaryRedirect(location=new_url)
