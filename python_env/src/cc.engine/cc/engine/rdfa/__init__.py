from zope.publisher.browser import BrowserPage
from zope.interface import implements
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

            license = component.getUtility(ILicenseSelector, license_class).\
                process_form(self.request.form)

        else:
            # get the license object based on the URI
            for selector in component.getUtilitiesFor(ILicenseSelector):
                license = selector[1].by_uri(license_uri)

                if license is not None:
                    break

        return IRdfaGenerator(license).with_form(self.request.form)

class Metadata(object):
    implements(IRdfaGenerator)

    IMAGE_BASE = "http://labs.creativecommons.org/zero/images"

    def __init__(self, license):
        self.license = license

    def with_form(self, request_form):
        """Generate RDFa metadata for the license adapted along with the
        form."""

        # see if a named adapter has been registered that provides
        # license class-specific functionality
        
        generator = component.queryAdapter(self.license, IRdfaGenerator,
                                           self.license.license_class, None)

        if generator is not None:
            return generator.with_form(request_form)

        # XXX someday we'll have the default implementation here
        raise Exception()
