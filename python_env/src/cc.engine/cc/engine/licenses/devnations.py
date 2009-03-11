from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility
from zope.publisher.browser import BrowserPage
from zope.app.pagetemplate import ViewPageTemplateFile

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class DevNations(BrowserLicense):
    """Browser License for Developing Nations licenses."""

class DevNationsDeed(LicenseDeed):
    __call__ = ViewPageTemplateFile('devnations_templates/deed.pt')

    
