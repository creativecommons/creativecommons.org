"""Support class for simple /characteristic views.  These are referenced
in many translations, so we need to continue to support the URLs."""

from zope.interface import implements
import interfaces
        
class Characteristics(object):
    implements(interfaces.ICharacteristic)

    
