Tech Overview
=============

cc.engine uses a very minimalist "web framework"... perhaps more
correctly, it does not use a framework at all, and instead provides a
very minimal WSGI application that pulls together several generic
libraries.  Despite this, the structure of the system is very similar
to a Pylons or a Django application.

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

The meat of the application is all housed in cc/engine/.


WSGI Application
~~~~~~~~~~~~~~~~

The WSGI application is housed in cc/engine/app.py.

It is a very minimalist WSGI application... it simply takes an
incoming request, passes the path to the routing system and sees if it
can find a result.  If it does find such a result


Routes
~~~~~~



Views / Controllers
~~~~~~~~~~~~~~~~~~~

Views / controllers are simply methods.

Generally kept in a component like 


Templates
~~~~~~~~~






Tests
~~~~~

