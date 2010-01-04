import StringIO

from nose.tools import assert_raises
from lxml import etree

import cc.license
from cc.engine import util

class FakeAcceptLanguage(object):
    def __init__(self, best_matches):
        self._best_matches = best_matches

    def best_matches(self):
        return self._best_matches


class FakeRequest(object):
    def __init__(self, best_matches):
        self.accept_language = FakeAcceptLanguage(best_matches)


def test_get_xpath_attribute():
    tree = etree.parse(
        StringIO.StringIO('<foo><bar><baz basil="herb" /></bar></foo>'))
    assert util._get_xpath_attribute(tree, '/foo/bar/baz', 'basil') == 'herb'


def test_get_locale_identity_data():
    identity_data = util.get_locale_identity_data(
        FakeRequest(['en-US_POSIX']))

    assert identity_data['language'] == 'en'
    assert identity_data['territory'] == 'US'
    assert identity_data['variant'] == 'POSIX'
    assert identity_data['script'] == None


def test_get_locale_text_orientation():
    # Make sure rtl languates are accepted as rtl
    assert util.get_locale_text_orientation(
        FakeRequest(['he-il'])) == u'rtl'

    # Make sure ltr languates are accepted as ltr
    assert util.get_locale_text_orientation(
        FakeRequest(['en'])) == u'ltr'

    # Make sure rtl language first is rtl
    assert util.get_locale_text_orientation(
        FakeRequest(['he-il', 'en'])) == u'rtl'

    # Make sure ltr language first is ltr
    assert util.get_locale_text_orientation(
        FakeRequest(['en', 'he-il'])) == u'ltr'

    # Make sure unknown/imaginary languages are ignored
    assert util.get_locale_text_orientation(
        FakeRequest(['foo-bar', 'he-il'])) == u'rtl'
    assert util.get_locale_text_orientation(
        FakeRequest(['foo-bar', 'en'])) == u'ltr'

    # If only an unknown/imaginary language is given, default to ltr
    assert util.get_locale_text_orientation(
        FakeRequest(['foo-bar'])) == u'ltr'

    # If only an no language is given, default to ltr
    assert util.get_locale_text_orientation(
        FakeRequest([])) == u'ltr'


def test_get_license_conditions():
    # TODO: we should test for all license possibilities here in
    #   several languages..

    expected = [
        {'char_title': 'Attribution',
         'char_brief': (
                "You must attribute the work in the manner specified "
                "by the author or licensor (but not in any way that suggests "
                "that they endorse you or your use of the work)."),
         'icon_name': 'by',
         'char_code': 'by',
         'predicate': 'cc:requires',
         'object': 'http://creativecommons.org/ns#Attribution'}]
    result = util.get_license_conditions(
        cc.license.by_code('by'))
    assert result == expected


def test_active_languages():
    {'code': 'en', 'name': u'English'} in util.active_languages()


def test_locale_to_cclicense_style():
    assert util.locale_to_cclicense_style('en-US') == 'en_US'
    assert util.locale_to_cclicense_style('en') == 'en'
    assert util.locale_to_cclicense_style('EN-us') == 'en_US'


def test_safer_resource_filename():
    assert util.safer_resource_filename(
        'cc.engine', 'templates/test/bunnies.pt').endswith(
        'templates/test/bunnies.pt')
    assert_raises(
        util.UnsafeResource,
        util.safer_resource_filename,
        'cc.engine', '../../templates/test/bunnies.pt')
