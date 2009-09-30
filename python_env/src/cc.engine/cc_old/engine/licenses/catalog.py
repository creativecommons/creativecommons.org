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

from cc.engine import interfaces

from cc.engine.licenses.standard import BrowserLicense
from cc.engine.licenses.publicdomain import PublicDomain
from cc.engine.licenses.fsf import FsfLicense
from cc.engine.licenses.sampling import SamplingLicense
from cc.engine.licenses.devnations import DevNations
from cc.engine.licenses.mitbsd import MitBsdLicense

class LicenseCatalog(object):
    implements(interfaces.ILicenseCatalog)

class LicenseCatalogTraverser(object):
    """Browser traverser for IMySite."""

    adapts(interfaces.ILicenseCatalog, IBrowserRequest)
    implements(IBrowserPublisher)

    DEFAULT_LICENSE = BrowserLicense
    CUSTOM_DEEDS = {
        'publicdomain' : PublicDomain,
        'sampling' : SamplingLicense,
        'GPL' : FsfLicense, # software
        'LGPL' : FsfLicense,# software
        'devnations' : DevNations,
        'MIT' : MitBsdLicense, # software
        'BSD' : MitBsdLicense, # software
        }

    def __init__(self, context, request):
        self.context = context
        self.request = request

    def browserDefault(self, request):
        return self.context, (getDefaultViewName(self.context, request),)
        
    def publishTraverse(self, request, name):

        # see if we know that this is a special license code
        if name in self.CUSTOM_DEEDS:
            return self.CUSTOM_DEEDS[name](self, [name])

        # check for sampling
        if name.find('sampling') >= 0:
            return SamplingLicense(self, [name])
        
        # see if this is a view
        view = queryMultiAdapter((self.context, request), name=name)
        if view is not None:
            return view

        # nope, must be a license
        return self.DEFAULT_LICENSE(self, [name])


