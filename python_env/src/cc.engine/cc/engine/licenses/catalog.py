import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine import interfaces
from cc.engine.licenses.standard import BrowserLicense
from cc.engine.licenses.publicdomain import PublicDomain

class LicenseCatalog(grok.Application, grok.Container):
    implements(interfaces.ILicenseCatalog)

    def traverse(self, code):

        if code == 'publicdomain':
            return PublicDomain(self, [code])
        
        return BrowserLicense(self, [code])

class Index(grok.View):
    grok.context(LicenseCatalog)
    grok.template('licenses-index')


