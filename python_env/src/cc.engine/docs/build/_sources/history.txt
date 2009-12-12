History
=======

History!

So, cc.engine has a long history to it, but prior to this version
cc.engine was a Zope 3 application.  And prior to that, it was other
things, possibly derived from a mystical ether of butterflies and
puppies.

Maybe the most interesting and relevant of this is why cc.engine is
structured the way it currently is.  For some reason I suspect that if
someone else is to look at this in the future, they may wonder, "Why
is this not a Django application?", presumably because Django is still
the cool hip thing to program in in the python world, contains a lot
of reusable cool components that can *only* work together in a Django
sense, because it is what most python programmers know, and because
gosh darn it people like it (and justifyably so).  Well, there are a
couple of reasons for why cc.engine is the way it is.

Django provides a lot of nice things: a generic user system, sessions,
provides a database layer, etc.  However, what cc.engine does is
fairly minimal: it serves licenses and their rdf files and has a
license chooser.  It doesn't have a database in the SQL sense, but it
does have one in the RDF sense..




cc.engine was rewritten during the "sanity" overhaul.  During this
period, many pieces of CC infrastructure were being rewritten to
accomodate cc.engine.

