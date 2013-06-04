import email
import StringIO

from nose.tools import assert_raises
from lxml import etree
from webob import Request
import nose

import cc.license
from cc.engine import util


util._activate_testing()


class FakeAcceptLanguage(object):
    def __init__(self, best_matches):
        self._best_matches = best_matches

    def best_matches(self):
        return self._best_matches


class FakeRequest(object):
    def __init__(self, best_matches):
        self.accept_language = FakeAcceptLanguage(best_matches)


def test_get_target_lang_from_request():

    def pick_lang(langs=[], form_lang=None):
        """Shorthand helper function thing."""
        environ = {
            "REQUEST_METHOD" : "GET",
            "PATH_INFO" : "/",
            "HTTP_ACCEPT_LANGUAGE" : ", ".join(langs),
            }
        if form_lang:
            environ["QUERY_STRING"] = "lang="+form_lang
        req = Request(environ)
        req.matchdict = {}
        return util.get_target_lang_from_request(req, default_locale='default')

    # don't crash when the environment variables are blank
    req = Request.blank("/")
    lang = util.get_target_lang_from_request(req, default_locale='default')
    assert lang == 'default'

    # default language case
    assert pick_lang() == 'default'

    # amurican english
    assert pick_lang(['en-us', 'en']) == 'en_US'

    # spanish
    assert pick_lang(['es']) == 'es'

    # http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
    assert pick_lang(['da, en-gb;q=0.8, en;q=0.7']) == 'da'

    # moar english
    assert pick_lang(['en-bs']) == 'en'

    # bs
    assert pick_lang(['total_bs_locale']) == 'default'

    # lower upper
    assert pick_lang(['es_ES']) == 'es_ES'

    # lower lower
    assert pick_lang(['es-es']) == 'es_ES'

    # specific language request
    assert pick_lang(['es', 'el'], form_lang='jp') == 'jp'


def test_get_xpath_attribute():
    tree = etree.parse(
        StringIO.StringIO('<foo><bar><baz basil="herb" /></bar></foo>'))
    assert util._get_xpath_attribute(tree, '/foo/bar/baz', 'basil') == 'herb'


def test_get_locale_identity_data():
    identity_data = util.get_locale_identity_data('en-US_POSIX')

    assert identity_data['language'] == 'en'
    assert identity_data['territory'] == 'US'
    assert identity_data['variant'] == 'POSIX'
    assert identity_data['script'] == None


def test_get_locale_text_orientation():
    # Make sure rtl languates are accepted as rtl
    assert util.get_locale_text_orientation('he-il') == u'rtl'

    # Make sure ltr languates are accepted as ltr
    assert util.get_locale_text_orientation('en') == u'ltr'

    # If only an unknown/imaginary language is given, default to ltr
    assert util.get_locale_text_orientation('foo-bar') == u'ltr'


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


def test_safer_resource_filename():
    assert util.safer_resource_filename(
        'cc.engine', 'templates/test/bunnies.html').endswith(
        'templates/test/bunnies.html')
    assert_raises(
        util.UnsafeResource,
        util.safer_resource_filename,
        'cc.engine', '../../templates/test/bunnies.html')


def test_send_email():
    util._clear_test_inboxes()

    # send the email
    util.send_email(
        "sender@creativecommons.org",
        ["amanda@example.org", "akila@example.org"],
        "Testing is so much fun!",
        """HAYYY GUYS!

I hope you like unit tests JUST AS MUCH AS I DO!""")

    # check the main inbox
    assert len(util.EMAIL_TEST_INBOX) == 1
    message = util.EMAIL_TEST_INBOX.pop()
    assert message['From'] == "sender@creativecommons.org"
    assert message['To'] == "amanda@example.org, akila@example.org"
    assert message['Subject'] == "Testing is so much fun!"
    assert message.get_payload() == """HAYYY GUYS!

I hope you like unit tests JUST AS MUCH AS I DO!"""

    # Check everything that the FakeMhost.sendmail() method got is correct
    assert len(util.EMAIL_TEST_MBOX_INBOX) == 1
    mbox_dict = util.EMAIL_TEST_MBOX_INBOX.pop()
    assert mbox_dict['from'] == "sender@creativecommons.org"
    assert mbox_dict['to'] == ["amanda@example.org", "akila@example.org"]
    mbox_message = email.message_from_string(mbox_dict['message'])
    assert mbox_message['From'] == "sender@creativecommons.org"
    assert mbox_message['To'] == "amanda@example.org, akila@example.org"
    assert mbox_message['Subject'] == "Testing is so much fun!"
    assert mbox_message.get_payload() == """HAYYY GUYS!

I hope you like unit tests JUST AS MUCH AS I DO!"""


SILLY_LICENSE_HTML = """This work available under a
<a href="http://example.org/goes/nowhere">very silly license</a>."""

def test_send_license_info_email():
    util._clear_test_inboxes()

    util.send_license_info_email(
        'Creative Commons Very-Silly License 5.8',
        SILLY_LICENSE_HTML,
        'ilovesillylicenses@example.org', 'en')

    assert len(util.EMAIL_TEST_INBOX) == 1
    message = util.EMAIL_TEST_INBOX.pop()
    assert message['From'] == "info@creativecommons.org"
    assert message['To'] == "ilovesillylicenses@example.org"
    assert message['Subject'] == "Your Creative Commons License Information"
    
    normal_payload = """Thank you for using a Creative Commons legal tool for your work.

You have selected Creative Commons Very-Silly License 5.8.
You should include a reference to this on the web page that includes
the work in question.

Here is the suggested HTML:

This work available under a
<a href="http://example.org/goes/nowhere">very silly license</a>.

Tips for marking your work can be found at
http://wiki.creativecommons.org/Marking.  Information on the supplied HTML and
metadata can be found at http://wiki.creativecommons.org/CC_REL.

This email and tech support has been brought to you by the nonprofit folks at
Creative Commons. CC relies on donations to provide you with licenses and
services like this. Please consider a donation to our annual fund:
https://creativecommons.net/donate.

Thank you!
Creative Commons Support
info@creativecommons.org"""
    campaign_payload = """Thank you for using a Creative Commons legal tool for your work.\n\nYou have selected Creative Commons Very-Silly License 5.8.\nYou should include a reference to this on the web page that includes\nthe work in question.\n\nHere is the suggested HTML:\n\nThis work available under a\n<a href="http://example.org/goes/nowhere">very silly license</a>.\n\nTips for marking your work can be found at\nhttp://wiki.creativecommons.org/Marking.  Information on the supplied HTML and\nmetadata can be found at http://wiki.creativecommons.org/CC_REL.\n\nThis email and tech support has been brought to you by the nonprofit folks at\nCreative Commons. CC relies on donations to provide you with licenses and\nservices like this. Please consider a donation to our annual fund:\nhttps://creativecommons.net/donate.\n\nThank you!\nCreative Commons Support\ninfo@creativecommons.org"""

    assert message.get_payload() in [normal_payload, campaign_payload]
            


def test_subset_dict():
    expected = {
        'keeper1': 'keepme1',
        'keeper2': 'keepme2'}

    result = util.subset_dict(
        {'keeper1': 'keepme1',
         'loser1': 'loseme1',
         'keeper2': 'keepme2',
         'loser2': 'loseme2'},
        ['keeper1', 'keeper2', 'keeper3'])

    assert result == expected


def test_publicdomain_partner_get_params():
    result = util.publicdomain_partner_get_params({'lang': 'en'})
    assert result == 'lang=en'

    # ignore garbage parameters
    result = util.publicdomain_partner_get_params({'lang': 'en', 'floobie': 'blech'})
    assert result == 'lang=en'

    result = util.publicdomain_partner_get_params(
        {'lang': 'en',
         'partner': 'http://nethack.org/',
         'exit_url': 'http://nethack.org/return_from_cc?license_url=[license_url]&license_name=[license_name]',
         'stylesheet': 'http://nethack.org/yendor.css',
         'extraneous_argument': 'large mimic'})

    result_pieces = result.split('&')
    assert len(result_pieces) == 4

    assert 'lang=en' in result_pieces
    assert 'partner=http%3A%2F%2Fnethack.org%2F' in result_pieces
    assert 'exit_url=http%3A%2F%2Fnethack.org%2Freturn_from_cc%3Flicense_url%3D%5Blicense_url%5D%26license_name%3D%5Blicense_name%5D' in result_pieces
    assert 'stylesheet=http%3A%2F%2Fnethack.org%2Fyendor.css' in result_pieces


def test_catch_license_versions_from_request():
    # Request with just a code
    request = Request.blank('/')
    request.matchdict = {
        'code': 'by'}
    license_versions = util.catch_license_versions_from_request(request)
    license_uris = [lic.uri for lic in license_versions]

    nose.tools.assert_equal(
        license_uris,
        ['http://creativecommons.org/licenses/by/1.0/',
         'http://creativecommons.org/licenses/by/2.0/',
         'http://creativecommons.org/licenses/by/2.5/',
         'http://creativecommons.org/licenses/by/3.0/'])

    # Request with a code and valid jurisdiction
    request = Request.blank('/')
    request.matchdict = {
        'code': 'by',
        'jurisdiction': 'es'}
    license_versions = util.catch_license_versions_from_request(request)
    license_uris = [lic.uri for lic in license_versions]

    nose.tools.assert_equal(
        license_uris,
        ['http://creativecommons.org/licenses/by/2.0/es/',
         'http://creativecommons.org/licenses/by/2.1/es/',
         'http://creativecommons.org/licenses/by/2.5/es/',
         'http://creativecommons.org/licenses/by/3.0/es/'])

    # Request with a code and bogus jurisdiction
    request = Request.blank('/')
    request.matchdict = {
        'code': 'by',
        'jurisdiction': 'zz'}
    license_versions = util.catch_license_versions_from_request(request)
    license_uris = [lic.uri for lic in license_versions]

    nose.tools.assert_equal(
        license_uris,
        ['http://creativecommons.org/licenses/by/1.0/',
         'http://creativecommons.org/licenses/by/2.0/',
         'http://creativecommons.org/licenses/by/2.5/',
         'http://creativecommons.org/licenses/by/3.0/'])

    # Request with a bogus code
    request = Request.blank('/')
    request.matchdict = {
        'code': 'AAAAA'}
    license_versions = util.catch_license_versions_from_request(request)
    license_uris = [lic.uri for lic in license_versions]

    nose.tools.assert_equal(
        license_uris, [])

    # Request with a bogus code and bogus jurisdiction
    request = Request.blank('/')
    request.matchdict = {
        'code': 'AAAAA', 'jurisdiction': 'FUUUUUUU'}
    license_versions = util.catch_license_versions_from_request(request)
    license_uris = [lic.uri for lic in license_versions]

    nose.tools.assert_equal(
        license_uris, [])
    
