from zope.interface import Interface, Attribute

class ILicenseSelector(Interface):
    """License selection for a particular class of license."""

    def get_form():
        """Return a form schema for use with this class of license."""

    def process_form(form):
        """Process the selection form and return an object implementing 
        ILicense."""

    def by_uri(uri, absolute=True):
        """Process a URI and return the appropriate ILicense object.
        If unable to produce a License from the URI, return None."""

class ILicense(Interface):
    """License metadata for a specific license."""

    name = Attribute(u"The human readable name for this license.")
    version = Attribute(u"The number version for the license.")
    jurisdiction = Attribute(u"The jurisdiction for the license.")
    uri = Attribute(u"The fully qualified URI of the license.")
    
