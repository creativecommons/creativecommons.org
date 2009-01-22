from zope.interface import Interface, Attribute

class ILicenseSelector(Interface):
    """License selection for a particular class of license."""

    #def get_form():
    #    """Return a zope.formlib Form which reflects the form schema
    #    and will return an ILicense instance when processed."""

    def by_uri(uri, absolute=True):
        """Process a URI and return the appropriate ILicense object.
        If unable to produce a License from the URI, return None."""

    def process_form(form):
        """Process a request form, returning an ILicense object."""

class ILicense(Interface):
    """License metadata for a specific license."""

    license_class = Attribute(u"The license class this license belongs to.")

    name = Attribute(u"The human readable name for this license.")
    version = Attribute(u"The number version for the license.")
    jurisdiction = Attribute(u"The jurisdiction for the license.")
    uri = Attribute(u"The fully qualified URI of the license.")
    
    libre = Attribute(u"Returns True if this is a 'Libre' license.")
