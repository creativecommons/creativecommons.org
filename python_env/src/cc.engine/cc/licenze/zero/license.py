from zope.interface import implements
from cc.license.exceptions import LicenseException

import interfaces

_ = unicode

class Zed(object):
    """License object for CC0 licenses.
    """

    implements(interfaces.ILicense)

    URI_BASE = 'http://labs.creativecommons.org/licenses/'

    def __init__(self, path):

        # validate the path used
        if len(path) != 3:
            # we *must* have an id, version, jurisdiction
            raise LicenseException("Invalid URL.")

        self._id, self._version, self._jurisdiction = path

        # validate each piece
        if self._id not in ('zero-waive', 'zero-assert'):
            raise LicenseException("Invalid URL.")

        if self._version != '1.0':
            raise LicenseException("Invalid URL.")

        if self._jurisdiction != 'us':
            raise LicenseException("Invalid URL.")

    @property
    def name(self):

        if self.code == 'zero-waive':
            return _("CC&empty; Waiver 1.0 United States")
        else:
            return _("CC&empty; Assertion 1.0 United States")

    @property
    def version(self):

        return self._version

    @property
    def jurisdiction(self):

        return self._jurisdiction

    @property 
    def default_locale(self):
        """Return the default locale for this license, typically based on 
        jurisdiction."""

        return 'en'

    @property
    def uri(self):

        return "%s/%s/%s/%s/" % (self.URI_BASE, self._id, self._version,
                                 self._jurisdiction)

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

