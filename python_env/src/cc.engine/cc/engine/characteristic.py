"""Support class for simple /characteristic views.  These are referenced
in many translations, so we need to continue to support the URLs."""

from cc.engine.chooser import BaseBrowserView
import grok

class Characteristic(grok.Model):
    """A single Characteristic (by, nc, etc); this is mostly just a marker
    object to connect views to the appropriate strings."""

    def __init__(self, name):
        self.name = name
        
class CharacteristicRoot(grok.Application, grok.Container):

    def traverse(self, name):

        return Characteristic(name)

class Characteristic_Popup(BaseBrowserView):

    _pt = ViewPageTemplateFile('templates/popup.pt')
    
    def __call__(self):

        # YYY set the key so Results._issue works right
        self.request.form['publicdomain'] = True
        
        return self._pt(self)
    
