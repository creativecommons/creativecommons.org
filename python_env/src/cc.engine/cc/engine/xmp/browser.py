from zope.component import getMultiAdapter
from zope.publisher.browser import BrowserPage

from cc.engine.xmp.interfaces import IXMPPresentation

class XmpView(BrowserPage):

    def __call__(self):

        license = self.context.issue(self.request)
        response = self.request.response
        
        response.setHeader('Content-Type', 'application/xmp; charset=UTF-8')
        response.setHeader('Content-Disposition',
                           u'attachment; filename="CC_%s.xmp"' %
                           license.name.strip().replace(' ', '_'));

        return getMultiAdapter((self.context, self.request),
                               IXMPPresentation)
    
