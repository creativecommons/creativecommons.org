from zope.interface import Interface, Attribute

class ILicenseEngine(Interface):

    def default_jurisdiction(language):
        """Return the default jurisdiction based on a user's locale."""

class ILicenseCatalog(Interface):
    pass

class ILicense(Interface):
    pass

class IDefaultJurisdiction(Interface):
    """The default jurisdiction for a particular request."""

    def getJurisdictionId():
        """Return the default jurisdiction ID."""
