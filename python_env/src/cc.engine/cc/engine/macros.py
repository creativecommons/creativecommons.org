import grok
from zope.interface import Interface

class Engine(grok.View):
    grok.context(Interface)

## class Partner(grok.View):
##     grok.context(Interface)
        
class Deed(grok.View):
    grok.context(Interface)
    
class Support(grok.View):
    """Container for support macros: translations, etc."""
    grok.context(Interface)
    
class Metadata(grok.View):
    """Metadata support macros."""
    grok.context(Interface)

    
