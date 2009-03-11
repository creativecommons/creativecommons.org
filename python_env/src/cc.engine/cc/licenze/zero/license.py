from zope.interface import implements
from cc.license.exceptions import LicenseException

from cc.licenze import interfaces

class Zed(object):
    """License object for CC0 licenses.
    """

    implements(interfaces.ILicense)

    URI_BASE = 'http://creativecommons.org/publicdomain'

    def __init__(self, path):

        # validate the path used
        if len(path) != 2:
            # we *must* have an id and version only
            raise LicenseException("Invalid URL.")

        self._id, self._version = path

        # validate each piece
        if self._id not in ('zero', 'pd'):
            raise LicenseException("Invalid URL.")

        if self._version != '1.0':
            raise LicenseException("Invalid URL.")


        # set up name, etc for the specific license
        if self._id == 'pd':
            self._name = "Public Domain Assertion"

        elif self._id == 'zero':
            self._name = "CC0 1.0 Universal"

    @property
    def license_class(self):

        return 'zero'

    @property
    def name(self):

        return self._name

    @property
    def version(self):

        return self._version

    @property
    def jurisdiction(self):

        return None

    @property
    def libre(self):

        return True

    @property 
    def default_locale(self):
        """Return the default locale for this license, typically based on 
        jurisdiction."""

        return 'en'

    @property
    def uri(self):

        return "%s/%s/%s/" % (self.URI_BASE, self._id, self._version,)

    @property
    def code(self):
        """Return the license code for this license."""

        return self._id

    @property
    def superseded(self):
        """Return True/False if this license has been superseded by a new
        version."""

        return False

    @property
    def deprecated(self):
        """Return True/False if this license has been deprecated."""

        return False

    @property
    def current_version(self):
        """Return a License object for the current version of this license;
        if the license has not been superseded, this will return self."""

        return self.version

    @property
    def imageurl(self):

        return ''

    @property
    def rdf(self):

        return ''

    @property
    def work_rdf(self):

        return ''

    @property
    def html(self):
	"""Strip the <html> tags from the html xml element returned
	by the web service."""

        return ''

