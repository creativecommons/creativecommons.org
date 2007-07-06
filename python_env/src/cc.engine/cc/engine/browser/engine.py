from zope.publisher.browser import BrowserPage
from zope.app.pagetemplate import ViewPageTemplateFile

class Index(BrowserPage):
    """License Engine index."""

    target_lang = ''

    def selected_jurisdiction(self):
        """Return the appropriate default jurisdiction -- either one explicitly
        requested by the user, or a good guess based on their language."""

        return "-"
    
    def __call__(self):

        # determine if we're using the standard site or partner interface
        if u'partner' in self.request.form:
            return ViewPageTemplateFile('partner.pt')(self)

        else:
            return ViewPageTemplateFile('index.pt')(self)
        
        response = self.request.response
        response.setHeader('Content-Type', 'text/plain')
        return repr(self.context)

    
    
