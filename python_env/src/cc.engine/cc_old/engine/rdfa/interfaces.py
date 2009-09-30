from zope.interface import Interface

class IRdfaGenerator(Interface):

    def with_form(request_form):
        """Generate RDFa metadata for the license adapted along with the
        form."""
