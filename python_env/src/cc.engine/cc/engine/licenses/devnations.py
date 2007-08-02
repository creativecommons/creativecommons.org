import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class DevNations(BrowserLicense):
    """Browser License for Developing Nations licenses."""

class DevNationsDeed(LicenseDeed):
    grok.context(DevNations)
    grok.name('index')
    grok.template('deed')
    
