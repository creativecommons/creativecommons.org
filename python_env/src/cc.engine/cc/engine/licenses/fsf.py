from zope.interface import implements
from zope.publisher.interfaces import NotFound
from zope.i18n import translate
from zope.i18n.interfaces import ITranslationDomain
from zope.component import queryUtility
from zope.publisher.browser import BrowserPage
from zope.app.pagetemplate import ViewPageTemplateFile

from cc.engine.licenses.standard import BrowserLicense, LicenseDeed

class FsfLicense(BrowserLicense):
    """Browser License for Free Software Foundation licenses."""

class FsfDeed(LicenseDeed):

    __call__ = ViewPageTemplateFile('fsf_templates/deed.pt')

    
