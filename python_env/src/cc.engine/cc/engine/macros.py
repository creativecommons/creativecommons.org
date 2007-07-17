import grok
from zope.interface import Interface

class Engine(grok.View):
    grok.context(Interface)

## class Partner(grok.View):
##     grok.context(Interface)
        
## class Deed(grok.View):
##     grok.context(Interface)
    
