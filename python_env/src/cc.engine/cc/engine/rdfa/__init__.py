from zope.publisher.browser import BrowserPage
from zope import component

from interfaces import IRdfaGenerator

class View(BrowserPage):

    def __call__(self):
        """Return the HTML+RDFa for the license + work metadata."""

        self.request.response.setHeader(
            'Content-Type', 'text/html; charset=UTF-8')
        license_uri = self.request.form['license-uri']

        return component.getUtility(IRdfaGenerator, license_uri)(
            license_uri, self.request.form)
