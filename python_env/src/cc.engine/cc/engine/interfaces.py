from zope.interface import Interface

class ILicenseEngine(Interface):

    def default_jurisdiction(language):
        """Return the default jurisdiction based on a user's locale."""

    def jurisdictions(launched_only=True):
        """Return a list of jurisdiction codes."""
        
class ILicenseCatalog(Interface):
    pass
