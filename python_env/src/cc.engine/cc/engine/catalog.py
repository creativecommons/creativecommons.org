import grok
from zope.interface import implements

from cc.engine.interfaces import ILicenseCatalog

class LicenseCatalog(grok.Application, grok.Container):
    implements(ILicenseCatalog)

