"""Internalization support for cc.engine."""

from zope.interface import implements
from zope.i18n.interfaces import IUserPreferredLanguages
from zope.publisher.browser import BrowserLanguages
from zope.app.publisher.browser import key as annotation_key

import cc.license.support

from cc.engine.interfaces import IDefaultJurisdiction

class PreferredLanguages(BrowserLanguages):
    """Custom language selector: looks for a language setting in the
    following order:

    * /deed.xx in the path
    * ?lang=xx querystring
    * ?language=xx querystring
    * cc_org_lang cookie
    * HTTP_ACCEPT header

    Based on zope.publisher.browser.BrowserLanguages
    """

    implements(IUserPreferredLanguages)

    def getPreferredLanguages(self):
        languages_data = self._getLanguagesData()
        if "overridden" in languages_data:
            # if the language has been overridden by, say, ++lang++
            print 'overridden: ', languages_data['overridden']
            return languages_data["overridden"]

        elif "cached" not in languages_data:

            # actually do our calculation here; we only cache the result
            # if we're using one of the query string parameters
            
            # see if we're dealing with deed.xx
            path_pieces = self.request['PATH_INFO'].rsplit('.', 1)

            if len(path_pieces) == 2 and len(path_pieces[1]) == 2:
                languages_data["cached"] = [path_pieces[1]]

            elif self.request.form.get(u'lang', False):
                # check for the query string
                languages_data["cached"] = [self.request['lang'], 'en']

            elif self.request.form.get('language', False):
                languages_data["cached"] = [self.request['language'], 'en']

                # XXX look for the cookie
            else:
                # fall back to default selection (HTTP_ACCEPT_LANGUAGE)
                return super(
                    PreferredLanguages, self).getPreferredLanguages()

        return languages_data["cached"]

    def _getLanguagesData(self):
        annotations = self.request.annotations
        languages_data = annotations.get(annotation_key)
        if languages_data is None:
            annotations[annotation_key] = languages_data = {}
        return languages_data

        
class PreferredJurisdictionByLocale(object):
    """Adapts a Request to IDefaultJurisdiction, allowing us to make a
    guess at what jurisdiction to use based on preferred locale."""

    implements(IDefaultJurisdiction)
    
    def __init__(self, request):
        self.request = request

    def getJurisdictionId(self):
        """Return the ID of the default jurisdiction, based on the
        preferred language of the request."""
        
        try:
            # ZZZ We shouldn't have to re-cast here, but we do. Sad.
            return cc.license.support.lang_to_jurisdiction(
                IUserPreferredLanguages(self.request).getPreferredLanguages()[0]
                )
        
        except IndexError:
            # no preferred language to map from
            return '-'
        
