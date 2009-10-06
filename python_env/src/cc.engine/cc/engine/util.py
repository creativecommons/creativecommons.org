import os

from cc.license.formatters.pagetemplate import CCLPageTemplateFile

BASE_TEMPLATE_DIR = os.path.join(os.path.dirname(__file__), 'templates')

_I18N_SETUP = FALSE

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
