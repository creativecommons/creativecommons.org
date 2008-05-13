import urllib2
import locale

locale.setlocale(locale.LC_ALL, '')

import grok
from zope.interface import Interface
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.license.decorators import memoized
from cc.engine import i18n

class Engine(grok.View):
    """Skin macros for the standard license engine."""
    grok.context(Interface)

class Partner(grok.View):
    """Skin macros for the partner interface."""
    grok.context(Interface)
    
class Popup(grok.View):
    """Page-level macros for popup pages."""
    grok.context(Interface)
        
class Deed(grok.View):
    """Skin macros for the license deeds."""
    grok.context(Interface)
    
class Support(grok.View):
    """Container for support macros: translations, etc."""
    grok.context(Interface)

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
            
            name = domain.translate('lang.%s' % code, target_language=code)
            # Asheesh, look into the comparison below.  This error gets emitted
            # when running mkdeeds:
            # 
            # /home/nkinkade/cc/devel/cc.engine/branches/production/cc/engine/macros.py:66:
            # UnicodeWarning: Unicode unequal comparison failed to convert both
            # arguments to Unicode - interpreting them as being unequal
            #  if name != 'lang.%s' % code:
            #
            # Setting sys.setdefaultencoding('utf-8') in sitecustomize.py fixes
            # this.
            # (nkinkade 2008-05-02)
            if name != 'lang.%s' % code:
                # we have a translation for this name...
                result.append(dict(code=code, name=name))

        return result
    
class Metadata(grok.View):
    """Metadata support macros."""
    grok.context(Interface)

    
