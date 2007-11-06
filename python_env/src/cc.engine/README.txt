=========
cc.engine
=========

:Date: $LastChangedDate: 2006-11-21 11:23:54 -0500 (Tue, 21 Nov 2006) $
:Version: $LastChangedRevision: 4737 $
:Author: Nathan R. Yergler <nathan@creativecommons.org>
:Organization: `Creative Commons <http://creativecommons.org>`_
:Copyright: 
   2007, Nathan R. Yergler, Creative Commons; 
   licensed to the public under the `MIT license 
   <http://opensource.org/licenses/mit-license.php>`_.


cc.engine provides the Creative Commons license engine along with a set of
related scripts.  The scripts can be used for generating static versions of
the license deeds.


Installation
============

cc.engine uses `zc.buildout <http://python.org/pypi/zc.buildout>`_ to 
assemble the software and its dependencies.  For example ::

  $ python2.4 bootstrap/bootstrap.py
  $ ./bin/buildout

After the buildout process completes the application may be started using
the generated ``cc_engine`` script ::

  $ ./bin/cc_engine start

You can prevent the service from detaching from the console as a daemon with
the ``fg`` parameter ::

  $ ./bin/cc_engine fg


Development Mode
----------------

If you are working on developing cc.engine, a special buildout configuration
is provided.  This configuration differs from the default in the following
ways:

* Zope is configured to run in ``devmode``.
* A XXX report is generated at time of buildout.

You can build cc.engine for development by specifying the buildout configuration
on the command line ::

  $ ./bin/buildout -c dev.buildout.cfg


Building lxml + Dependencies
----------------------------

cc.engine relies of `lxml <http://codespeak.net/lxml>`_, which is a Python
wrapper for libxml2 and libxslt1.  If you system has older versions of these
libraries installed, cc.engine may fail with ``Unknown symbol`` errors.  A
specialized buildout configuration is provided to download and build a 
local version of libxml2, libxslt1 and lxml if needed.  To use this, specify
the configuration on the command line ::

  $ ./bin/buildout -c lxml.buildout.cfg

Note that this builds in production mode.


Scripts
=======

In order to improve performance, cc.engine provides scripts which may be used
to generate static versions of the license.  The script, ``mkdeeds``, is built
as part of the buildout process.  Run ::

  $ ./bin/mkdeeds -h

for a complete list of options and parameters.



