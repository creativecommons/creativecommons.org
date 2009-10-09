import os
import pkg_resources

from lxml import etree
from zope.i18n.translationdomain import TranslationDomain

from cc.license.formatters.pagetemplate import CCLPageTemplateFile

BASE_TEMPLATE_DIR = os.path.join(os.path.dirname(__file__), 'templates')

_I18N_SETUP = False

def get_zpt_template(template_path):
    setup_i18n_if_necessary()
    full_template_path = os.path.join(BASE_TEMPLATE_DIR, template_path)
    return CCLPageTemplateFile(full_template_path)
    

def setup_i18n_if_necessary():
    global _I18N_SETUP
    if _I18N_SETUP:
        return

    domain = TranslationDomain('cc.engine')
    # for catalog in os.listdir(I18N_PATH):

    #     catalog_path = os.path.join(I18N_PATH, catalog)

    #     po_path = os.path.join(catalog_path, 'cc.engine.po')
    #     mo_path = os.path.join(catalog_path, 'cc.engine.mo')
    #     if not os.path.isdir(catalog_path) or not os.path.exists(po_path):
    #         continue

    #     compile_mo_file('cc.engine', catalog_path)
        
    #     domain.addCatalog(GettextMessageCatalog(
    #             catalog, 'cc.engine', mo_path))

    # component.provideUtility(domain, ITranslationDomain, name='cc.engine')
    _I18N_SETUP = True


def get_locale_text_orientation(request):
    """
    Find out whether the locale is ltr or rtl
    """
    locale_filename = None

    for lang in request.accept_language.best_matches():
        split_lang = lang.split('-')
        language = split_lang[0].lower()
        if len(split_lang) == 2:
            language = language + u'_' + split_lang[1].upper()

        this_locale_filename = pkg_resources.resource_filename(
            u'zope.i18n.locales', u'data/%s.xml' % language)

        if os.path.exists(this_locale_filename):
            locale_filename = this_locale_filename
            break

    if not locale_filename:
        return u'ltr'

    locale_tree = etree.parse(file(locale_filename))
    try:
        char_orientation = locale_tree.xpath(
            '//orientation')[0].attrib['characters']
        if char_orientation == u'right-to-left':
            return u'rtl'
        else:
            return u'ltr'
    except IndexError:
        return u'ltr'
