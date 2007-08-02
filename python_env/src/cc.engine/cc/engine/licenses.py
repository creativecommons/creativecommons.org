import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

import cc.license
import i18n
from cc.license.exceptions import LicenseException

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

        # decode the version and jurisdiction
        if len(self.pieces) > 2:
            version, jurisdiction = self.pieces[1:3]
        elif len(self.pieces) > 1:
            version, jurisdiction = self.pieces[1], None
        
        # YYY cache me!
        return cc.license.LicenseFactory().by_license_code(self.pieces[0],
                                                           version,
                                                           jurisdiction)

    @property
    def conditions(self):
        """Return a sequence of mappings defining the conditions defined by
        this license."""

        attrs = []

        for lic in self.license.code.split('-'):

            # Go through the chars and build up the HTML and such
            char_title = translate ('char.%s_title' % lic,
                                    domain='icommons')
            char_brief = translate ('char.%s_brief' % lic,
                                    domain='icommons')

            icon_name = lic
            predicate = 'cc:requires'
            object = 'http://creativecommons.org/ns#Attribution'

            if lic == 'nc':
              predicate = 'cc:prohibits'
              object = 'http://creativecommons.org/ns#CommercialUse'
              if self.license.jurisdiction == 'jp':
                 icon_name = '%s-jp' % icon_name
              elif self.license.jurisdiction in ('fr', 'es', 'nl', 'at', 'fi', 'be', 'it'):
                 icon_name = '%s-eu' % icon_name
            elif lic == 'sa':
              object = 'http://creativecommons.org/ns#ShareAlike'
              if self.license.version == 3.0 and self.license.code == 'by-sa':
                char_brief = translate ('char.sa_bysa30_brief',
                                        domain='icommons')
            elif lic == 'nd':
              predicate = ''
              object = ''

            attrs.append({'char_title':char_title,
                          'char_brief':char_brief,
                          'icon_name':icon_name,
                          'char_code':lic,
                          'predicate':predicate,
                          'object':object,
                     })

        # YYY cache me!
        return attrs
        
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

    def update(self):
        """Prepare to render the deed."""

        # redirect if we don't have a trailing slash
        if self.request['PATH_INFO'][-1] != '/':
            if self.request.get('QUERY_STRING', ''):
                target = '%s/?%s' % (self.request['PATH_INFO'],
                                     self.request['QUERY_STIRNG'])
            else:
                target = '%s/' % self.request['PATH_INFO']
                
            return self.request.response.redirect(target)

        # make sure we've traversed to a valid license version
        try:
            self.context.license
        except LicenseException, e:
            raise NotFound(self.context, self.request['REQUEST_URI'],
                           self.request)

        # make sure we've extracted the locale from the request querystring
        self.request.setupLocale()

    @property
    def is_rtl(self):
        """Return 'rtl' if the request locale is represented right-to-left;
        otherwise return an empty string."""

        if self.request.locale.orientation.characters == u'right-to-left':
            return 'rtl'

        return ''

    @property
    def is_rtl_align(self):
        """Return the appropriate alignment for the request locale:
        'right' or 'left'."""

        return self.request.locale.orientation.characters.split('-')[0]

    @property
    def active_languages(self):
        """Return a sequence of tuples:

        (language_code, uri, language_name)

        for each available language; the uri is the localized version of the
        current view in the particular language."""

        # YYY cache me
        
        domain = queryUtility(ITranslationDomain, i18n.I18N_DOMAIN)
        lang_codes = domain.getCatalogsInfo().keys()
        lang_codes.sort()
        
        return [dict(code=n,
                     url='%sdeed.%s' % (self.context.license.uri,n) ,
                     name=domain.translate('lang.%s' % n, target_language=n))
                 
                 for n in lang_codes]


    @property
    def color(self):
        """Return the "color" of the license; the color reflects the relative
        amount of freedom."""
        
        # YYY cache me!
        license_code = self.context.license.code
        
        if license_code.lower() in ('devnations', 'sampling'):
           return 'red'
       
        elif license_code.find('sampling') > -1 or \
                 license_code.find('nc') > -1 or \
                 license_code.find('nd') > -1:
           return 'yellow'
       
        else:
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


