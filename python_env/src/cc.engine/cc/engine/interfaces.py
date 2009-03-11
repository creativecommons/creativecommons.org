from zope.interface import Interface, Attribute

class ICharacteristic(Interface):
    pass

class ILicenseEngine(Interface):

    def default_jurisdiction(language):
        """Return the default jurisdiction based on a user's locale."""

    def license_class(class_name):
        pass

    def issue(request):
        """Extract the license engine fields from the request and return a
        License object."""        

    def generate_hash(email_addr, title, holder):
        pass
    
    def send_pd_confirmation(next_url, email_addr, title, holder,
                             lang='en'):
        """Sends the confirmation email to the PD dedicator."""

    def send_pd_dedication(email_addr, title, holder, lang='en'):
        """Send the public domain dedication after confirmation."""


class ILicenseCatalog(Interface):
    pass

class ILicense(Interface):
    pass

class IBrowserLicense(Interface):
    pieces = Attribute("")
    license = Attribute("")

    def add_piece(piece):
        """Append a piece to the traversal stack."""

class IDefaultJurisdiction(Interface):
    """The default jurisdiction for a particular request."""

    def getJurisdictionId():
        """Return the default jurisdiction ID."""
