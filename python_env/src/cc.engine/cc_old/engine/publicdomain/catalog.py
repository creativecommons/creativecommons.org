from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from zope.component import adapts, queryMultiAdapter
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.publisher.interfaces.browser import IBrowserRequest, IBrowserPublisher
from zope.app.publisher.browser import getDefaultViewName

from cc.engine.licenses import catalog
import interfaces

from zero import CCZero

class PublicDomainCatalog(object):
    implements(interfaces.IPublicDomainCatalog)

class PublicDomainCatalogTraverser(catalog.LicenseCatalogTraverser):
    """Browser traverser for IPublicDomainCatalog."""

    adapts(interfaces.IPublicDomainCatalog, IBrowserRequest)
    implements(IBrowserPublisher)

    DEFAULT_LICENSE = CCZero
    CUSTOM_DEEDS = {}


