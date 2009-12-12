Tech Overview
=============

cc.engine uses a very minimalist "web framework"... perhaps more
correctly, it does not use a framework at all, and instead provides a
very minimal WSGI application that pulls together several generic
libraries.  Despite this, the structure of the system is very similar
to a Pylons or a Django application.  If you have written a Pylons of
Django application before, all of this should look fairly familiar
with little extra information.

(However, if you are really curious about how this works, or would
like to try writing your own framework or minimalist un-framework web
application, I recommend reading `Another Do-It-Yourself Framework
<http://pythonpaste.org/webob/do-it-yourself.html>`_)


Components used
---------------

The components that are used are:

* `Zope Page Templates <http://pypi.python.org/pypi/zope.pagetemplate/3.5.0>`_
  for templating
* `Routes <http://routes.groovie.org/>`_ for url dispatching
* `WebOb <http://pythonpaste.org/webob/>`_ for making request objecst
  within WSGI pleasant.
* `Nose <http://somethingaboutorange.com/mrl/projects/nose/0.11.1/>`_
  for nice unit testing


How the app is structured
-------------------------

The meat of the application is all housed in cc/engine/.  Several
parts of the application are broken into "subapplications", such as
cc/engine/licenses/, which houses the routing information and views
for the actual licenses and their deeds, and cc/engine/chooser/, which
houses the views and routing information for the license chooser.


WSGI Application
~~~~~~~~~~~~~~~~

The WSGI application is housed in cc/engine/app.py.

It is a very minimalist WSGI application... it simply takes an
incoming request, passes the path to the routing system and sees if it
can find a result.  If it can't find a result at that URL, but can
find one if it appends a slash, it'll redirect to that url (thus
adding Django-style APPEND_SLASH=True functionality).  The application
will then pass a WebOb Request object to the view/controller specified
by the matching route's "controller" field.


URL Dispatching / Routing
~~~~~~~~~~~~~~~~~~~~~~~~~

The root mapper for routes is set up in the cc.engine.routing module
as the "mapping" global variable.  Some routes may be provided here,
but the majority are actually housed in the "subapplication"
routing.py files.  For example, routing for the chooser is provided in
cc/engine/chooser/routing.py and routing for the license is provided
in cc/engine/chooser/license.py.  These are then "pulled in" to the
global routing mapper via the mapping.extend() method.  The "results"
of the routing match will then be appended to the webob.Request object
that is passed to the view as the "matchdict" attribute.  See the
`documentation for the Routes library
<http://routes.groovie.org/manual.html>`_ to find out more about how
this works.

Generally, you can provide any information here, but there is one
attribute which is *required* as part of cc.engine's WSGI application:
"controller".  This field should be structured as:
"module.path.to:controller_name", where the portion before the colon
is the module and the portion after the colon is the
function/method/callable object instance that is to process this
method.


Views / Controllers
~~~~~~~~~~~~~~~~~~~

Views / controllers are simply methods.  They must accept a single
method, "request", which will be the webob.Request method passed from
the WSGI application.  In turn, they are expected to return a
webob.Response object.  (Or, in the case of an redirect or error,
webob.exc.HTTPTemporaryRedirect or something else from webob.exc.)

Views technically *can* be placed anywhere, but by convention you
should probably put them in a 'views' file (ie,
cc.engine.license.views, or cc.engine.chooser.views).


Templates
~~~~~~~~~

Templates are kept in cc/engine/templates/.  All templates are
currently Zope Page Template based.

To load a template, use the cc.engine.util.get_zpt_template() method.
Pass in a filename that is relative to cc/engine/templates/.  For
example, to import the template at
cc/engine/templates/myth_profiles/phoenix.zpt run:

  phoenix_template = util.get_zpt_template('myth_profiles/phoenix.zpt')

If you know the target language that your template should render to,
pass that in as the second argument (see I18N below).

To render, use:

  context = {
      'request': request,
      'some_other_context_var': 'bla bla bla'}
  phoenix_template.pt_render(context)


cc.engine tries to be fairly minimal in the amount of Zope machinery
it pulls in (ie, currently it does not use the zope component system),
so currently if your template relies on metal macros, you should
provide those in the context like so:

  context = {
      'request': request,
      'base_template': util.get_zpt_template(
          'myth_profiles/base.pt')}

Then in your template, use metal:use-macro like:

  <html xmlns="http://www.w3.org/1999/xhtml"
        xmlns:tal="http://xml.zope.org/namespaces/tal"
        xmlns:i18n="http://xml.zope.org/namespaces/i18n"
        xmlns:metal="http://xml.zope.org/namespaces/metal"
        metal:use-macro="base_template/macros/page"
        i18n:domain="cc_org">

Obviously replacing "page" with whatever macro is appropriate.

TODO: explain how to use assets like javascript/css/images.  Guess we
should implement this first!


I18N
~~~~

Internationalization


Models
~~~~~~

Surprise!  Cc.engine is not (at least presently) a database-driven
application.  The only "models" used are actually the licenses pulled
from the RDF files via cc.license.


Tests
~~~~~

Tests go in the cc/engine/tests/ directory.  Either add to an existing
test_*.py module or add your own if appropriate.  Tests are set up in
the usual Nose tests fashion.
