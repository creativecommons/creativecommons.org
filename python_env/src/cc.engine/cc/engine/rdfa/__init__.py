from zope.publisher.browser import BrowserPage
from zope import component

from interfaces import IRdfaGenerator
from cc.licenze.interfaces import ILicenseSelector

class View(BrowserPage):

    def __call__(self):
        """Return the HTML+RDFa for the license + work metadata."""

        self.request.response.setHeader(
            'Content-Type', 'text/html; charset=UTF-8')

        license_uri = self.request.form.get('license-uri', None)

        if license_uri is None:
            # we don't have a license URI; assume we need to issue
            license_class = self.request.form.get('license-class', None)

            if license_class is None:
                raise Exception()

            license = component.getUtility(ILicenseSelector, 'zero').\
                process_form(self.request.form)

        else:
            # get the license object based on the URI
            for selector in component.getUtilitiesFor(ILicenseSelector):
                license = selector[1].by_uri(license_uri)

                if license is not None:
                    break

        return IRdfaGenerator(license).with_form(self.request.form)
