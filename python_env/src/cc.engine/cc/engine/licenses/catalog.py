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

class LicenseCatalog(grok.Application, grok.Container):
    implements(interfaces.ILicenseCatalog)

    CUSTOM_DEEDS = dict(
        publicdomain = PublicDomain,
        GPL = FsfLicense,
        LGPL = FsfLicense,
        )
    
    def traverse(self, code):

        if code in self.CUSTOM_DEEDS:
            return self.CUSTOM_DEEDS[code](self, [code])
        
        return BrowserLicense(self, [code])

class Index(grok.View):
    grok.context(LicenseCatalog)
    grok.template('licenses-index')


