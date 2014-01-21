"""cc.license implementation for Public Domain Assertion."""
from urlparse import urlparse

from zope.interface import implements
from zope.component import *

from cc.license.exceptions import LicenseException

import cc.licenze.interfaces as interfaces
import license

class Selector(object):
    implements(interfaces.ILicenseSelector)

    def get_form(self):
        """Return a form schema for use with this class of license."""

        raise NotImplementedError()

    def by_uri(self, uri, absolute=True):
        """Process a URI and return the appropriate ILicense object."""

        if not(absolute):
            # only compare the path
            if (urlparse(uri)[2] == 
                urlparse(license.PublicDomainAssertion.URI)[2]):
                
                return license.PublicDomainAssertion()

        if uri == license.PublicDomainAssertion.URI:
            return license.PublicDomainAssertion()
        
        return None

    def process_form(self, form):
        """Process the form from a request and return the ILicense object."""

        # PD Assertion is simple -- only one license available
        return license.PublicDomainAssertion()

                           

