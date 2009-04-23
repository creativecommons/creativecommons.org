## Copyright (c) 2007-2009 Nathan R. Yergler, Creative Commons

## Permission is hereby granted, free of charge, to any person obtaining
## a copy of this software and associated documentation files (the "Software"),
## to deal in the Software without restriction, including without limitation
## the rights to use, copy, modify, merge, publish, distribute, sublicense,
## and/or sell copies of the Software, and to permit persons to whom the
## Software is furnished to do so, subject to the following conditions:

## The above copyright notice and this permission notice shall be included in
## all copies or substantial portions of the Software.

## THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
## IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
## FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
## AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
## LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
## FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
## DEALINGS IN THE SOFTWARE.

from setuptools import setup, find_packages

import sys

cc_licenze_deps = [
    'setuptools', 
    'zope.component',
    'zope.interface',
    ]

setup(
    name = "cc.engine",
    version = "8.01-dev",
    packages = ['cc.engine', 'cc.licenze'],
    namespace_packages = ['cc',],
    
    # scripts and dependencies
    install_requires = cc_licenze_deps + ['setuptools',

                        # 'ZODB3',
                        'ZConfig',
                        'zdaemon',
                        'zope.publisher',
                        'zope.traversing',
                        #'zope.app.twisted',
                        #'zope.app.appsetup',
                        #'zope.app.zcmlfiles',
                        # The following packages aren't needed from the
                        # beginning, but end up being used in most apps
                        #'zope.annotation',
                        #'zope.copypastemove',
                        #'zope.formlib',
                        'zope.i18n',
                        'zope.sendmail',
                        #'zope.app.authentication',
                        # 'zope.app.session',
                        #'zope.app.intid',
                        #'zope.app.keyreference',
                        #'zope.app.catalog',
                        # The following packages are needed for functional
                        # tests only
                        'zope.testing',
                        'zope.app.testing',
                        'zope.app.securitypolicy',
                        'zope.testbrowser',
                        'z3c.i18n',

                        'cc.license',
                        'Genshi',
                        'lxml>2.0',
                        'WebTest',
                        ],

      entry_points = """
      [console_scripts]
      ccengine-debug = cc.engine.startup:interactive_debug_prompt
      ccengine-ctl = cc.engine.startup:zdaemon_controller
      cc_engine = cc.engine.startup:zdaemon_controller
      mkdeeds = cc.engine.scripts.deeds:cli
      i18nextract = cc.engine.i18n.extract:main

      [paste.app_factory]
      main = cc.engine.startup:application_factory

      """,

    # author metadata
    author = 'Nathan R. Yergler',
    author_email = 'nathan@creativecommons.org',
    description = '',
    license = 'MIT',
    url = 'http://creativecommons.org',

    )
