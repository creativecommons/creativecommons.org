import os
import csv

from zope.interface import Interface, implements
from zope import component
from zope.i18n import ITranslationDomain, translate
from zope.i18n.locales import LoadLocaleError
import zope.schema.interfaces

class IIso3166(Interface):

    def country_list(target_lang='en'):
        """Return a sequence of two-tuples containing a country code 
        and name."""

class Z3cIso3166(object):
    """z3c.i18n based implementation of IIso3166."""
    implements(IIso3166)
    
    countries_locale = {}

    def __init__(self):
        self._i18n = self._vocabulary = None

    def country_list(self, target_lang='en'):
        """Return a sequence of two-tuples containing a country code 
        and name."""

        if self._i18n is None:
            self._i18n = component.getUtility(ITranslationDomain,
                                              'z3c.i18n.iso.territories')

            self._vocabulary = component.getUtility(
                zope.schema.interfaces.IVocabularyFactory,
                'z3c.i18n.iso.territories')(None)

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
            
            self.countries_locale[target_lang] = sorted(result, key=lambda x:x[1])

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


    def __getitem__(self, key):

        return self._codes[key]

    def country_list(self, target_lang='en'):
        """Return a sequence of two-tuples containing a country code 
        and name."""

        return self._data
