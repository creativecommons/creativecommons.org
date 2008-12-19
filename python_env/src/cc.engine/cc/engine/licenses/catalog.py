import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine import interfaces

from cc.engine.licenses.standard import BrowserLicense
from cc.engine.licenses.publicdomain import PublicDomain
from cc.engine.licenses.fsf import FsfLicense
from cc.engine.licenses.sampling import SamplingLicense
from cc.engine.licenses.devnations import DevNations
from cc.engine.licenses.mitbsd import MitBsdLicense

class LicenseCatalog(grok.Application, grok.Container):
    implements(interfaces.ILicenseCatalog)

    CUSTOM_DEEDS = dict(
        publicdomain = PublicDomain,
        sampling = SamplingLicense,
        GPL = FsfLicense,
        LGPL = FsfLicense,
        devnations = DevNations,
        MIT = MitBsdLicense,
        BSD = MitBsdLicense,
        )
    
    def traverse(self, code):

        # short-circuit for known views
        if code in ('disclaimer-popup', 'index', 'index.html', 'index.rdf'):
            return None

        if code in self.CUSTOM_DEEDS:
            return self.CUSTOM_DEEDS[code](self, [code])

        # check for sampling
        if code.find('sampling') >= 0:
            return SamplingLicense(self, [code])
        
        return BrowserLicense(self, [code])


