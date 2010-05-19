import re

from lxml import html
import webtest

from cc.engine import app, staticdirect


TESTAPP = webtest.TestApp(
    app.CCEngineApp(
        staticdirect.RemoteStaticDirect('/static/'), {}))


def scraped_launched_jurisdictions():
    international_dom = html.parse('http://creativecommons.org/international/')
    completed_block = international_dom.xpath(
        "id('blocks')/div/div[@class='icontainer']")[0]
    jurisdictions = [
        a.attrib['href'].strip('/').split('/')[1]
        for a in completed_block.xpath("div/a")]

    return jurisdictions
    

def scraped_expected_licenses(jurisdiction):
    juris_deed_re = re.compile(
        '^(?:http://creativecommons.org)?(/licenses/[^/]+/[^/]+/%s/)$' % (
            jurisdiction))

    url = 'http://creativecommons.org/international/%s/' % jurisdiction
    jurisdiction_etree = html.parse(url)

    deed_urls = []

    for a in jurisdiction_etree.xpath('//a'):
        deed_match = juris_deed_re.match(a.attrib['href'])
        if deed_match:
            deed_urls.append(deed_match.groups()[0])

    return deed_urls


def test_scraped_launched_jurisdictions():
    """
    Test to make sure that scraped_launched_jurisdictions is still
    pulling stuff.

    Yes, testing a test utility.
    """
    jurisdictions = scraped_launched_jurisdictions()

    # A few relatively safe jurisdictions just to be sure this works
    assert 'us' in jurisdictions
    assert 'fr' in jurisdictions


def test_jurisdiction_dropdown_contains_jurisdictions():
    """
    Make sure that all the jurisdictions listed in
    scraped_launched_jurisdictions show up in the jurisdictions
    dropdown.
    """
    response = TESTAPP.get('/choose/')
    body_etree = html.fromstring(response.body)
    dropdown_jurisdictions = [
        o.attrib['value']
        for o in body_etree.xpath(
            "//select[@name='field_jurisdiction']/option")]

    # make sure unported/international jursidiction is there, but
    # remove it for comparison
    dropdown_jurisdictions.remove('')

    scraped_jurisdictions = scraped_launched_jurisdictions()

    assert set(scraped_jurisdictions) == set(dropdown_jurisdictions)


def test_licenses_exist():
    for jurisdiction in scraped_launched_jurisdictions():
        deed_urls = scraped_expected_licenses(jurisdiction)
        for url in deed_urls:
            response = TESTAPP.get(url)
        
