import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope import component

from cc.licenze.interfaces import ILicenseSelector

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class PublicDomain(BrowserLicense):

    @property
    def license(self):
        """DEPRECATED

        Return the cc.license.License object selected."""

        # YYY convert pieces into URL... should there be a utility for this?
        pieces = ['', 'licenses'] + self.pieces + ['']
        
        return component.getUtility(ILicenseSelector, 'publicdomain').by_uri(
            '/'.join(pieces), False)

class PublicDomainDedication(LicenseDeed):
    grok.context(PublicDomain)
    grok.name('index')
    grok.template('deed')
    
    @property
    def license(self):
        """Return the cc.license.License object selected; note that this
        is part of the context, as we need to pass in the request locale
        to localize the license name."""

        # YYY convert pieces into URL... should there be a utility for this?
        pieces = ['', 'licenses'] + self.context.pieces + ['']
        
        return component.getUtility(ILicenseSelector, 'publicdomain').by_uri(
            '/'.join(pieces), False)

    @property
    def conditions(self):
        return []

    @property
    def color(self):
        """Return the "color" of the license; the color reflects the relative
        amount of freedom."""

        return 'green'
