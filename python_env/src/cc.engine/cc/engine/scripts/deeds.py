import os
import sys
import optparse
import unittest

from zope.component import queryUtility
from zope.testing import doctest
from zope.i18n.negotiator import normalize_lang
from zope.i18n.interfaces import ITranslationDomain
from zope.app.testing.functional import (FunctionalTestSetup, ZCMLLayer,
                                         getRootFolder, FunctionalDocFileSuite)

import cc.license
import cc.engine
from cc.engine import i18n

LICENSES_XML = os.path.join(os.path.dirname(cc.engine.__file__),
                            'scripts', 'license_xsl', 'licenses.xml')

SERVER_BASE = "http://localhost:8080/"

ftesting_zcml = os.path.join(os.path.dirname(cc.engine.__file__), 'ftesting.zcml')
CcEngineFunctionalLayer = ZCMLLayer(ftesting_zcml, __name__, 'CcEngineFunctionalLayer')

def create_option_parser():
    """Return an optparse.OptionParser configured for the mkdeeds script."""

    parser = optparse.OptionParser()

    # license selection options
    parser.add_option('-v', '--version', dest='versions', default=None,
                      help='comma separated list of versions to generate')
    parser.add_option('-j', '--jurisdiciton', dest='jurisdictions', 
                      default=None,
                      help='comma separated list of jurisdictions to generate.')
    parser.add_option('-c', '--code', dest='codes', default=None,
                      help='comma separated list of license codes to generate.')
    parser.add_option('-u', '--uri', dest='single_uri', default=None,
                      help='a single license URI to [re-]generate.')

    # locale selection options
    parser.add_option('-l', '--locales', dest='locales', default=None,
                      help='comma separated list of locales to generate')

    # output options
    parser.add_option('-o', '--output-dir', dest='output_dir', 
                      default='licenses',
                      help='root output directory.')

    return parser

def get_licenses(options):
    """Return a sequence of license URIs to retrieve, based on the options
    object passed in. See L{create_option_parser} for a description of 
    available options."""

    # see if we just want a single license
    if options.single_uri is not None:
        return [options.single_uri]

    # get the list of all licenses
    licenses = cc.license.LicenseFactory().all()

    # filter by version
    if options.versions is not None:
        versions = [float(v) for v in options.versions.split(',')]
        licenses = [l for l in licenses if 
                    l.version in versions]

    # filter by jurisdiction
    if options.jurisdictions is not None:
        jurisdictions = [j.strip().lower() 
                         for j in options.jurisdictions.split(',')]
        licenses = [l for l in licenses if l.jurisdiction in jurisdictions]

    # filter by code
    if options.codes is not None:
        codes = [c.strip() for c in options.codes.split(',')]
        licenses = [l for l in licenses if l.code in codes]

    # return the list
    return [l for l in licenses]

def get_locales(options = None):
    """Return a sequence of locales to generate deeds for."""

    # determine the list of licenses + locales to generate deeds for
    if options is None or options.locales is None:
        # all locales
        domain = queryUtility(ITranslationDomain, i18n.I18N_DOMAIN)
        return [str(n) for n in domain.getCatalogsInfo().keys()]

    else:
        return [normalize_lang(n) for n in options.locales.split(',')]

def license_locale_uri(license, locale):
    """Given a license URI and a target locale, return the locale URI that
    will render the deed in the specified locale."""

    CC_URL = 'http://creativecommons.org/'

    return "%s++vh++http:creativecommons.org:80/++/%s?lang=%s" % (
        SERVER_BASE, license.uri[len(CC_URL):], locale)

    return '%sdeed?lang=%s' %(license.uri.replace(CC_URL,
                                               SERVER_BASE), 
                               locale)

def save_deed(output_dir, license, locale, contents):
    """Save the deed to disk and update the mulitview mapping file."""

    # determine the output file path
    output_path = '%sdeed.%s' % (
        os.path.join(output_dir, license.uri.split('licenses/')[1]),
        locale)

    # make sure the directory structure exists
    if not(os.path.exists(os.path.dirname(output_path))):
        os.makedirs(os.path.dirname(output_path))

    # write the file
    file(output_path, 'w').write(contents)

    # see if this is the default locale; if so, create a second copy
    if (license.default_locale == locale) or \
            (not(license.default_locale in get_locales()) and 
             normalize_lang(license.default_locale).split('-')[0] == locale):
        file(output_path.rsplit('.', 1)[0], 'w').write(contents)

def cli():
    """Generate static deed files."""

    # parser the command line options
    (options, args) = create_option_parser().parse_args()

    # determine the absolute output dir
    output_dir = os.path.abspath( os.path.join( 
            os.getcwd(), options.output_dir)
                                  )

    # run the "test" suite
    cur_dir = os.getcwd()
    os.chdir(os.path.dirname(__file__))

    suite = unittest.TestSuite()
    test = FunctionalDocFileSuite(
         'mkdeeds.txt',
         globs=dict(getRootFolder=getRootFolder,
                    save_deed = lambda x, y, z:save_deed(output_dir, x, y, z),
                    get_licenses = lambda:get_licenses(options),
                    get_locales = lambda:get_locales(options),
                    license_locale_uri = license_locale_uri,
                    ),
         optionflags = (doctest.ELLIPSIS
                        | doctest.REPORT_NDIFF
                        | doctest.NORMALIZE_WHITESPACE),)
    test.layer = CcEngineFunctionalLayer
    suite.addTest(test)

    unittest.TextTestRunner().run(suite)

    # restore the initial working directory
    os.chdir(cur_dir)
