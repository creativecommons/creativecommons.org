from cc.engine.chooser import BaseBrowserView as BrowserPage
from cc.engine.chooser import ResultsView

from cc.engine.rdfa import zero
from zope import component

from cc.engine.rdfa.interfaces import IRdfaGenerator
from cc.licenze.interfaces import ILicenseSelector

class ZeroChooser(BrowserPage):

    def __call__(self):
        return getattr(self, 'index.html')()

    @property
    def action(self):
        return self.request.form.get('zero-action', '.')

    def can_issue(self):
        """Inspect the request and see if we have enough information to
        issue a waiver."""

        # make sure they've confirmed their understanding
        confirm = self.request.form.get('confirm', False)
        
        return confirm is not False

    def issue(self):

        # we don't have a license URI; assume we need to issue
        license_class = self.request.form.get('license-class', None)

        if license_class is None:
            raise Exception()

        license = component.getUtility(ILicenseSelector, license_class).\
            process_form(self.request.form)

        return license

    def rdfa(self):

        return IRdfaGenerator(self.issue()).with_form(self.request.form)
        
