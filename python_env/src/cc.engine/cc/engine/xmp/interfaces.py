from zope.interface import Interface

class IXMPPresentation(Interface):
    """Presentation layer interface for XMP data."""

    def read(size=-1):
        """Read data from the file."""

    def tell():
        """Return the file's current position."""

    def seek(offset, whence=0):
        """Set the file's current position."""

        
