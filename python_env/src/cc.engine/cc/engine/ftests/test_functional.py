"""Functional test suite wrapper for cc.engine."""

import os
import unittest
import cc.engine
from zope.testing import doctest
from zope.app.testing.functional import (FunctionalTestSetup, ZCMLLayer,
                                         getRootFolder, FunctionalDocFileSuite)
import zope.testbrowser.browser
import zope.testbrowser.testing

ftesting_zcml = os.path.join(os.path.dirname(cc.engine.__file__), 'ftesting.zcml')
CcEngineFunctionalLayer = ZCMLLayer(ftesting_zcml, __name__, 'CcEngineFunctionalLayer')

def test_suite():
    suite = unittest.TestSuite()
    docfiles = ['initialize.txt', 'chooser.txt', 'deeds.txt']

    for docfile in docfiles:
        test = FunctionalDocFileSuite(
             docfile,
             globs=dict(getRootFolder=getRootFolder,
                        Browser=zope.testbrowser.testing.Browser),
             optionflags = (doctest.ELLIPSIS
                            | doctest.REPORT_NDIFF
                            | doctest.NORMALIZE_WHITESPACE),)
        test.layer = CcEngineFunctionalLayer
        suite.addTest(test)

    return suite

if __name__ == '__main__':
    unittest.main()

