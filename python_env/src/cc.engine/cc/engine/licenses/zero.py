import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed
import cc.license.interfaces

_ = unicode

class Zed(object):
    implements(cc.license.interfaces.ILicense)

    URI = 'http://creativecommons.org/licenses/zero/'

    @property
    def name(self):

        return _("CC0")

    @property
    def version(self):

        return '1.0'

    @property
    def jurisdiction(self):

        return '-'

    @property 
    def default_locale(self):
        """Return the default locale for this license, typically based on 
        jurisdiction."""

        return 'en'

    @property
    def uri(self):

        return self.URI

    @property
    def code(self):
        """Return the license code for this license."""

        pieces = self.URI.split('/')
        return pieces[pieces.index('licenses') + 1]

    @property
    def superseded(self):
        """Return True/False if this license has been superseded by a new
        version."""

        return False

    @property
    def deprecated(self):
        """Return True/False if this license has been deprecated."""

        return False

    @property
    def current_version(self):
        """Return a License object for the current version of this license;
        if the license has not been superseded, this will return self."""

        return self.version

    @property
    def imageurl(self):

        return ''

    @property
    def rdf(self):

        return ''

    @property
    def work_rdf(self):

        return ''

    @property
    def html(self):
	"""Strip the <html> tags from the html xml element returned
	by the web service."""

        return ''

class CCZero(BrowserLicense):

    @property
    def license(self):
        """DEPRECATED

        Return the cc.license.License object selected."""

        return Zed()


class CCZeroDeed(LicenseDeed):
    grok.context(CCZero)
    grok.name('index')
    grok.template('deed')
    

    @property
    def license(self):
        """Return the cc.license.License object selected; note that this
        is part of the context, as we need to pass in the request locale
        to localize the license name."""

        return Zed()

    @property
    def conditions(self):
        return []

    @property
    def color(self):
        """Return the "color" of the license; the color reflects the relative
        amount of freedom."""

        return 'green'
