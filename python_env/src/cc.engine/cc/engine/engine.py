from zope.interface import implements
from zope.component.factory import Factory
from persistent import Persistent

from cc.engine.interfaces import ILicenseEngine

class LicenseEngine(Persistent):
    implements(ILicenseEngine)
        
    def default_jurisdiction(self, language):
        return "-"
    
LicenseEngineFactory = Factory(
    LicenseEngine,
    title=u"License Engine",
    description=u""
    )
    
