from zope.interface import implements
from cc.license.exceptions import LicenseException

from cc.licenze import interfaces

class PublicDomainAssertion(object):
    """ILicense for Public Domain assertions."""

    implements(interfaces.ILicense)

    URI = u'http://creativecommons.org/licenses/publicdomain/'

    @property
    def license_class(self):
        
        return 'publicdomain'

    @property
    def name(self):

        return "Public Domain Assertion"

    @property
    def version(self):

        return None

    @property
    def jurisdiction(self):

        return None

    @property 
    def default_locale(self):
        """Return the default locale for this license, typically based on 
        jurisdiction."""

        return 'en'

    @property
    def uri(self):

        return self.URI

    @property
    def code(self):
        """Return the license code for this license."""

        return 'publicdomain'

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

