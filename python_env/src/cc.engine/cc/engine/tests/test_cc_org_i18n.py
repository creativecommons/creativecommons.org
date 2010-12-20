from zope.i18n import translate

from cc.i18n import ccorg_i18n_setup
from cc.i18n.util import negotiate_locale


def test_translate():
    assert translate(
        'char.by_title', domain=ccorg_i18n_setup.I18N_DOMAIN,
        target_language=negotiate_locale('en_US')) == 'Attribution'
