"""Internalization support for cc.engine."""

from zope.interface import implements
from zope.i18n.interfaces import IUserPreferredLanguages
from zope.publisher.browser import BrowserLanguages

I18N_DOMAIN = 'icommons'

class PreferredLanguages(object):
    """Custom language selector: looks for a language setting in the
    following order:

    * ?lang=xx querystring
    * ?language=xx querystring
    * cc_org_lang cookie
    * HTTP_ACCEPT header
    """
    implements(IUserPreferredLanguages)

    def __init__(self, request):
        self.request = request

    def getPreferredLanguages(self):

        # check for the query string
        if self.request.get('lang', False):
            return [self.request['lang'], 'en']

        if self.request.get('language', False):
            return [self.request['language'], 'en']
        
        # XXX look for the cookie
        
        # fall back to default selection (HTTP_ACCEPT_LANGUAGE)
        return BrowserLanguages(self.request).getPreferredLanguages()

