from zope.i18n import translate

from cc.engine import cc_org_i18n

def test_translate():
    assert translate(
        'char.by_title', domain=cc_org_i18n.I18N_DOMAIN,
        target_language='en_US') == 'Attribution'
