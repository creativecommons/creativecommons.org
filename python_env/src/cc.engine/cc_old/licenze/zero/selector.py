"""cc.license implementation for CC0."""
from urlparse import urlparse

from zope.interface import implements

from cc.license.exceptions import LicenseException

import cc.licenze.interfaces as interfaces
import license

class ZeroSelector(object):
    implements(interfaces.ILicenseSelector)

    def get_form(self):
        """Return a form schema for use with this class of license."""

        raise NotImplementedError()

    def by_uri(self, uri, absolute=True):
        """Process a URI and return the appropriate ILicense object."""

        pieces = urlparse(uri)
        path = pieces[2].split('/')

        try:
            # this will raise a LicenseException if the path is not valid
            return license.Zed(path[2:-1])
        except LicenseException:

            # XXX we *should* return None here, but until we fully implement
            # ILicenseSelector for all licenses, we need to raise an 
            # exception
            raise

            return None

    def process_form(self, form):
        """Process the form from a request and return a CC0 License object
        which implements ILicense.

        The Zero selector looks for the following fields:

          * code
        
            The base id of the license; either 'zero' or 'pd' for the
            CC0 Waiver or Public Domain Assertion, respectively.  If not
            specified, the CC0 Waiver is returned.
        """

        # CC0 is simple -- only one license available
        return license.Zed([form.get('code','zero'), 
                            form.get('version', '1.0')])
                           

