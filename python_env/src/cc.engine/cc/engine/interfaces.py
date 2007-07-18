from zope.interface import Interface

class ILicenseEngine(Interface):

    def default_jurisdiction(language):
        """Return the default jurisdiction based on a user's locale."""
        
class ILicenseCatalog(Interface):
    pass

class ILicense(Interface):
    pass
