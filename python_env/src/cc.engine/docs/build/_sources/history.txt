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
period, many pieces of CC infrastructure were being rewritten.  The
prior cc.engine was written in Zope 2, and we knew we wanted to move
away from that.  Deciding that Django provided a lot of things but
very few that were relevant to our needs, the original option that was
considered was repoze.bfg, a minimalist framework that makes use of
some zope components.  However, partway through implementing that it
was discovered that even the bits that were provided by repoze.bfg
were not really necessary and lead to a lot of code bloat just to try
to get them from interfering with what we did need, and that the
entire system could be constructed in a very minimal wsgi application.
And so, understanding what components were used and useful and what
components weren't, reducing the application to a very minimal wsgi
app wasn't so hard, and the end results were much cleaner.

And that's why things are the way they are, in case someone in the
future ever wants to know (or I forget!).
