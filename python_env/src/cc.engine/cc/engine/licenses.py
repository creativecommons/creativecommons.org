import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
import cc.license

from cc.engine import interfaces


class BrowserLicense(grok.Model):
    implements(interfaces.ILicense)

    TARGET_NAMES = ('deed', 'rdf', 'rdf-checksum',
                    'legalcode', 'legalcode-checksum')

    def __init__(self, parent, pieces):
        self.__parent__ = parent
        self.__name__ = pieces[-1]

        self.pieces = pieces
        
    @property
    def license(self):
        """Return the cc.license.License object selected."""

        version = jurisdiction = None
        
        return cc.license.LicenseFactory().by_license_code(self.pieces[0],
                                                           version,
                                                           jurisdiction)

    def traverse(self, name):

        if len(self.pieces) > 3:
            # no cases call for more than three steps of traversal
            return None

        # XXX handle deed.de, etc
        
        if name not in self.TARGET_NAMES:
            return BrowserLicense(self, self.pieces + [name])


class LicenseDeed(grok.View):
    grok.context(BrowserLicense)
    grok.name('index')
    grok.template('deed')
    
    @property
    def is_rtl(self):

        # XXX
        pass

    @property
    def is_rtl_align(self):

        # XXX
        pass

    @property
    def active_languages(self):

        # XXX
        return []

    
    @property
    def color(self):

        # XXX
        return 'green'
    

class LicenseRdf(grok.View):
    grok.context(BrowserLicense)
    grok.name('rdf')

    def render(self):
        return "rdf goes here"
                 
    
class LicenseCatalog(grok.Application, grok.Container):
    implements(interfaces.ILicenseCatalog)

    def traverse(self, code):

        return BrowserLicense(self, [code])

class Index(grok.View):
    grok.context(LicenseCatalog)
    grok.template('licenses-index')


