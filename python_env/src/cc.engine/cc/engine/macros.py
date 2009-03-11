import urllib2
import locale

locale.setlocale(locale.LC_ALL, '')

from zope.interface import Interface
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility
from zope.publisher.browser import BrowserView
from zope.app.pagetemplate import ViewPageTemplateFile

from cc.license.decorators import memoized
from cc.engine import i18n
    
class Support(BrowserView):
    """Container for support macros: translations, etc."""

    template = ViewPageTemplateFile('macros_templates/support.pt')

    def __getitem__(self, key):
        return self.template.macros[key]

    @property
    def campaign_total(self):
        # http://creativecommons.org/includes/total.txt

        total = urllib2.urlopen(
            'http://creativecommons.org/includes/total.txt').read().strip()
        
        return locale.format('$ %d', locale.atoi(total), True)
    
    @property
    @memoized
    def active_languages(self):
        """Return a sequence of dicts, where each element consists of the
        following keys:

        * code: the language code
        * name: the translated name of this language

        for each available language."""

        domain = queryUtility(ITranslationDomain, i18n.I18N_DOMAIN)
        lang_codes = domain.getCatalogsInfo().keys()
        lang_codes.sort()

        # this loop is long hand for clarity; it's only done once, so
        # te additional performance cost should be negligible
        result = []
        for code in lang_codes:

            if code == 'test': continue
            
            name = domain.translate('lang.%s' % code, target_language=code).\
                decode('utf-8')
            if name != u'lang.%s' % code:
                # we have a translation for this name...
                result.append(dict(code=code, name=name))

        return result

    
