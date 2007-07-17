import grok
from zope.interface import implements
from zope.publisher.interfaces import NotFound

from cc.engine.interfaces import ILicenseCatalog

class License(grok.Model):

    @property
    def license(self):
        """Return the cc.license.License object selected by the traversal."""
        return None
    
    def traverse(self, foo):
        self.code.append(foo)
        
        return self

class LicenseDeed(grok.View):
    grok.template('deed')
    grok.context(License)

    @property
    def is_rtl(self):
        pass

    @property
    def is_rtl_align(self):
        pass
    
class LicenseIndex(grok.View):
    grok.name('index')
    grok.context(License)
    
    def render(self):

        found = True
        if found:
            deed = LicenseDeed(self.context, self.request)
            deed.license = self.context

        raise NotFound(self.context, self.context.code)
    
class LicenseCatalog(grok.Application, grok.Container):
    implements(ILicenseCatalog)

    def traverse(self, foo):

        model = License()
        model.code = [foo]
        
        return model

class Index(grok.View):
    grok.context(LicenseCatalog)
    grok.template('licenses-index')


