import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class FsfLicense(BrowserLicense):
    """Browser License for Free Software Foundation licenses."""

class FsfDeed(LicenseDeed):
    grok.context(FsfLicense)
    grok.name('index')
    grok.template('deed')
    
