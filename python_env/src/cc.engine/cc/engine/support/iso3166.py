import os
import csv
import locale

from zope.interface import Interface, implements
from zope import component
from zope.i18n import ITranslationDomain, translate
from zope.i18n.locales import LoadLocaleError
import zope.schema.interfaces

class IIso3166(Interface):

    def country_list(target_lang='en'):
        """Return a sequence of two-tuples containing a country code 
        and name."""

    def get_name(token, target_lang='en'):
        """Return the translated name for the territory specified by
        token."""

def ensure_loaded(f):
    
    def preload(self, *args, **kwargs):
        if getattr(self, '_loaded', False):
            return f(self, *args, **kwargs)

        self._load()
        self._loaded = True

        return f(self, *args, **kwargs)

    return preload

class Z3cIso3166(object):
    """z3c.i18n based implementation of IIso3166."""
    implements(IIso3166)
    
    countries_locale = {}

    def _load(self):
        """Do the actual component lookups; defered to here so that our
        factory can run successfully.
        """

        #self._i18n = component.getUtility(ITranslationDomain,
        #                                  'z3c.i18n.iso.territories')

        self._vocabulary = component.getUtility(
            zope.schema.interfaces.IVocabularyFactory,
            'z3c.i18n.iso.territories')(None)

    @ensure_loaded
    def get_name(self, token, target_lang='en'):
        """Return the translated name for the territory specified by
        token."""

        name = self._vocabulary.getTerm(token)
        try:
            return translate(name.title, target_language=target_lang)
        except LoadLocaleError, e:
            return name.title.default

    @ensure_loaded
    def country_list(self, target_lang='en'):
        """Return a sequence of two-tuples containing a country code 
        and name."""

        try:
            result = self.countries_locale[target_lang]
        except KeyError:
            # add the result to the cache
            try:
                result = [(v.token, translate(v.title, target_language=target_lang)) 
                    for v in self._vocabulary]

            except LoadLocaleError, e:
                result = [(v.token, v.title.default) 
                    for v in self._vocabulary]

            result.sort(key=lambda x:locale.strxfrm(x[1]))
            self.countries_locale[target_lang] = result
        			
        return result

class Iso3166(object):
    """A utility that provides a mapping from country names to ISO-3166 
    country codes."""
    implements(IIso3166)

    def __init__(self):

        # read the country list from disk
        self._data = []
        self._codes = {}

        country_file = csv.reader(
            open(os.path.join(os.path.dirname(__file__), 'iso3166.csv'))
            )

        for country in country_file:
            self._data.append((country[0], country[1]))
            self._codes[country[0]] = country[1]

        self._data = tuple(self._data)



    def get_name(self, token, target_lang='en'):
        """Return the translated name for the territory specified by
        token."""

        return self._codes[token]

    def country_list(self, target_lang='en'):
        """Return a sequence of two-tuples containing a country code 
        and name."""

        return self._data
