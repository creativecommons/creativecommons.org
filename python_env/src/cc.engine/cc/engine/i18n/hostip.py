"""hostip.info-based support for cc.engine."""

from zope.interface import implements
from zope.i18n.interfaces import IUserPreferredLanguages
from zope.publisher.browser import BrowserLanguages
from zope.app.publisher.browser import key as annotation_key

import cc.license.support

from cc.engine.interfaces import IDefaultJurisdiction

class PreferredJurisdictionByHostIp(object):
    """Adapts a Request to IDefaultJurisdiction, allowing us to make a
    guess at what jurisdiction to use based on hostip.info information."""

    implements(IDefaultJurisdiction)
    
    def __init__(self, request):

        self.request = request

    def getJurisdictionId(self):
        """Return the ID of the default jurisdiction, based on the
        preferred language of the request."""

        remote_ip = self.request['REMOTE_ADDR']

        # get the country from the hostip.info database
        country = 'US'
        
        # get the country from the hostip database, map it to a jurisdiction;
        # lang_to_jurisdiction returns Unported ('-') if unable to map
        
        return cc.license.support.lang_to_jurisdiction(
            py_hostip.country(remote_ip)
            )
    
