import os
import csv

from zope.interface import Interface, implements

class IIso3166(Interface):

    def country_list():
        """Return a sequence of two-tuples containing a country code 
        and name."""

        
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

    def country_list(self):
        """Return a sequence of two-tuples containing a country code 
        and name."""

        return self._data
