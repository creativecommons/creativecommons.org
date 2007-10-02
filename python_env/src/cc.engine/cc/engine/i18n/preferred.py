"""Internalization support for cc.engine."""

import re

from zope.interface import implements, providedBy
from zope.i18n.interfaces import IUserPreferredLanguages
from zope.i18n.negotiator import normalize_lang
from zope.publisher.browser import BrowserLanguages
from zope.publisher.interfaces.browser import IBrowserApplicationRequest
from zope.app.publisher.browser import key as annotation_key

import cc.license.support

from cc.engine.interfaces import IDefaultJurisdiction

class PreferredLanguages(BrowserLanguages):
    """Custom language selector: looks for a language setting in the
    following order:

    * /deed.xx in the path -- mapped to ?lang=xx by Apache
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

            return languages_data["overridden"]

        elif "cached" not in languages_data:

            # actually do our calculation here; we only cache the result
            # if we're using one of the query string parameters
            path_pieces = self.request['PATH_INFO'].split('/')

            # see if we're dealing with a BrwoserRequest
            if IBrowserApplicationRequest in providedBy(self.request):
                # this request has a form available; look for query string, etc

                # XXX the form may not be processed yet, so lets double-check
                if self.request.form == {} and \
                   'licenses' in path_pieces and \
                   self.request.get('QUERY_STRING', '').find('lang=') > -1:

                    self.request.processInputs()

                if self.request.form.get(u'lang', False):
                    # check for the query string

                    languages_data["cached"] = [
                        normalize_lang(self.request['lang']), u'en']

                elif len(path_pieces) >= 6 and 'licenses' in path_pieces:
                    # /licenses doesn't do content negotiation;
                    # this request specifies a jurisdiction

                    languages_data["cached"] = [
                        normalize_lang(n) for n in
                        cc.license.support.default_locales(path_pieces[-2])] + \
                        [u'en']

                elif self.request.form.get('language', False):
                    languages_data["cached"] = [
                        normalize_lang(self.request['language']), u'en']

                    # XXX look for the cookie
                else:
                    # fall back to default selection (HTTP_ACCEPT_LANGUAGE)
                    return super(
                        PreferredLanguages, self).getPreferredLanguages()

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
        
