from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility
from zope.publisher.browser import BrowserPage
from zope.app.pagetemplate import ViewPageTemplateFile

from cc.license.decorators import memoized
from cc.engine import interfaces
from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class IMitBsdLicense(interfaces.IBrowserLicense):

    def is_mit():
        """Return True if the context is an MIT license."""

    def is_bsd():
        """Return True if the context is a BSD license."""

class MitBsdLicense(BrowserLicense):
    """Browser License for MIT/BSD licenses."""
    implements(IMitBsdLicense)

    @property
    @memoized
    def is_mit(self):
        """Return True if the context is an MIT license."""

        return "MIT" in self.license.uri

    @property
    @memoized
    def is_bsd(self):
        """Return True if the context is an BSD license."""

        return "BSD" in self.license.uri

    
class MitBsdDeed(LicenseDeed):

    __call__ = ViewPageTemplateFile('mitbsd_templates/deed.pt')
    
