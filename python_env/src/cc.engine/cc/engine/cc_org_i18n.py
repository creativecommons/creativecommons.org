import pkg_resources
import os

from zope.i18n.translationdomain import TranslationDomain
from zope.i18n.gettextmessagecatalog import GettextMessageCatalog
from zope.i18n.interfaces import ITranslationDomain
from zope.i18n.compile import compile_mo_file

from zope import component


DOMAIN_SETUP = False

I18N_PATH = pkg_resources.resource_filename(
    'cc.engine', 'i18n/po')
I18N_DOMAIN = 'cc_org'


def _setup_i18n():
    global DOMAIN_SETUP
    if DOMAIN_SETUP:
        return

    domain = TranslationDomain(I18N_DOMAIN)
    for catalog in os.listdir(I18N_PATH):

        catalog_path = os.path.join(I18N_PATH, catalog)

        po_path = os.path.join(catalog_path, I18N_DOMAIN + '.po')
        mo_path = os.path.join(catalog_path, I18N_DOMAIN + '.mo')
        if not os.path.isdir(catalog_path) or not os.path.exists(po_path):
            continue

        compile_mo_file(I18N_DOMAIN, catalog_path)
        
        print "providing: (%s, %s, %s)" % (
                catalog, I18N_DOMAIN, mo_path)

        domain.addCatalog(GettextMessageCatalog(
                catalog, I18N_DOMAIN, mo_path))

    component.provideUtility(domain, ITranslationDomain, name='cc_org')
    DOMAIN_SETUP = True


_setup_i18n()
