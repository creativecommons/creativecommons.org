import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class MitBsdLicense(BrowserLicense):
    """Browser License for MIT/BSD licenses."""

class MitBsdDeed(LicenseDeed):
    grok.context(MitBsdLicense)
    grok.name('index')
    grok.template('deed')
    
