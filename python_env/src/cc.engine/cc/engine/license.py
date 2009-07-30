import urllib

from zope.interface import implements
from zope.publisher.interfaces import IPublishTraverse

from zope.publisher.interfaces import NotFound
from zope.publisher.browser import BrowserPage

class Redirector(BrowserPage):
    implements(IPublishTraverse)

    def publishTraverse(self, request, name):

        return self

    def __call__(self):
        """If the request was a GET, simply rewrite /license to /choose;
        if the request was a POST, reconstruct the form data as a 
        query string and redirect."""

        new_url = self.request.getURL().replace("/license", "/choose")

        return self.request.response.redirect(
            "%s?%s" % (new_url, urllib.urlencode(self.request.form))
            )


