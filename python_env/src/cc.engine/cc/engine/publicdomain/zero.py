from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope import component
from zope.publisher.browser import BrowserPage
from zope.app.pagetemplate import ViewPageTemplateFile

from cc.licenze.interfaces import ILicenseSelector

from cc.license.exceptions import LicenseException

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed
import cc.license.interfaces

_ = unicode

class CCZero(BrowserLicense):

    @property
    def license(self):
        """DEPRECATED

        Return the cc.license.License object selected."""

        # YYY convert pieces into URL... should there be a utility for this?
        pieces = ['', 'licenses'] + self.pieces + ['']
        
        return component.getUtility(ILicenseSelector, 'zero').by_uri(
            '/'.join(pieces), False)


class CCZeroDeed(LicenseDeed):

    __call__ = ViewPageTemplateFile('zero_templates/deed.pt')
    
    @property
    def license(self):
        """Return the cc.license.License object selected; note that this
        is part of the context, as we need to pass in the request locale
        to localize the license name."""

        # YYY convert pieces into URL... should there be a utility for this?
        pieces = ['', 'licenses'] + self.context.pieces + ['']
        
        return component.getUtility(ILicenseSelector, 'zero').by_uri(
            '/'.join(pieces), False)

    @property
    def conditions(self):
        return []

    @property
    def color(self):
        """Return the "color" of the license; the color reflects the relative
        amount of freedom."""

        return 'green'
