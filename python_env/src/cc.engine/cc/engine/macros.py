import grok
from zope.interface import Interface
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine import i18n
from cc.engine.decorators import cached

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
    @cached
    def active_languages(self):
        """Return a sequence of dicts, where each element consists of the
        following keys:

        * code: the language code
        * name: the translated name of this language

        for each available language."""

        domain = queryUtility(ITranslationDomain, i18n.I18N_DOMAIN)
        lang_codes = domain.getCatalogsInfo().keys()
        lang_codes.sort()
        
        return [dict(code=n,
                     name=domain.translate('lang.%s' % n, target_language=n))
                 
                 for n in lang_codes
                if n != 'test']

    
class Metadata(grok.View):
    """Metadata support macros."""
    grok.context(Interface)

    
