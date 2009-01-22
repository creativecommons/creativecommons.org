"""cc.license implementation for CC0."""
from urlparse import urlparse

from zope.interface import implements
from zope.component import *

from cc.license.exceptions import LicenseException

import cc.licenze.interfaces as interfaces
import license

class ZeroSelector(object):
    implements(interfaces.ILicenseSelector)

    def get_form(self):
        """Return a form schema for use with this class of license."""

        raise NotImplementedError()

    def process_form(self, form):
        """Process the selection form and return an object implementing 
        ILicense."""

        raise NotImplementedError()

    def by_uri(self, uri, absolute=True):
        """Process a URI and return the appropriate ILicense object."""

        pieces = urlparse(uri)
        path = pieces[2].split('/')

        try:
            # this will raise a LicenseException if the path is not valid
            return license.Zed(path[2:-1])
        except LicenseException:
            return None


                           

