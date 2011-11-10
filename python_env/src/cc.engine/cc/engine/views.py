import urllib

from webob import Response, exc


def root_view(request):
    return Response("This is the root")


def license_redirect(request):
    new_url = '/choose/' + request.matchdict.get('remaining_url', '')

    request_form = request.GET or request.POST
    if request_form:
        new_url = '%s?%s' % (
            new_url, urllib.urlencode(request_form))
    return exc.HTTPMovedPermanently(location=new_url)


def work_html_redirect(request):
    new_url = '/choose/work-html-popup'
    if request.GET:
        new_url = '%s?%s' % (
            new_url, urllib.urlencode(request.GET))
    return exc.HTTPMovedPermanently(location=new_url)
