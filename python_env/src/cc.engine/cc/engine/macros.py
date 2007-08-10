import grok
from zope.interface import Interface

class Engine(grok.View):
    """Skin macros for the standard license engine."""
    grok.context(Interface)

class Partner_Skin(grok.View):
    """Skin macros for the partner interface."""
    grok.context(Interface)
    
class Popup(grok.View):
    """Page-level macros for popup pages."""
    grok.context(Interface)
        
class Deed(grok.View):
    """Skin macros for the license deeds."""
    grok.context(Interface)
    
class Support(grok.View):
    """Container for support macros: translations, etc."""
    grok.context(Interface)
    
class Metadata(grok.View):
    """Metadata support macros."""
    grok.context(Interface)

    
