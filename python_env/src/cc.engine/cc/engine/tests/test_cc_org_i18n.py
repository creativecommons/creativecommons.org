from zope.i18n import translate

from cc.i18n import ccorg_i18n_setup

def test_translate():
    assert translate(
        'char.by_title', domain=ccorg_i18n_setup.I18N_DOMAIN,
        target_language='en_US') == 'Attribution'
