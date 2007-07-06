from zope.interface import implements
from zope.component.factory import Factory
from persistent import Persistent

from cc.engine.interfaces import ILicenseCatalog

class LicenseCatalog(Persistent):
    implements(ILicenseCatalog)

LicenseCatalogFactory = Factory(
    LicenseCatalog,
    title=u"License Catalog",
    description=u""
    )

