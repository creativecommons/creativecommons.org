from zope.publisher.browser import BrowserPage
from zope.interface import implements
from zope.component.factory import Factory
from persistent import Persistent
from zope.component import adapts, queryMultiAdapter
from zope.interface import implements
from zope.publisher.interfaces import NotFound

from zope.publisher.interfaces import IPublishTraverse
from zope.app.publisher.browser import BrowserView

class CatalogTraversal(BrowserView):
    implements(IPublishTraverse)
    def __init__(self, context, request):
        self.context = context
        self.request = request
        self.traverse_subpath = []

    def publishTraverse(self, request, name):
        self.traverse_subpath.append(name)
        return self

    def __call__(self):
        """Just for example"""
        return ("traverse_subpath = %s\nrequest.URL = %s" %
                (self.traverse_subpath, self.request.URL))
