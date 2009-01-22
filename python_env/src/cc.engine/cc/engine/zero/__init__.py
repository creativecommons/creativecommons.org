from zope.publisher.browser import BrowserPage

from cc.engine.rdfa import zero

class ZeroChooser(BrowserPage):

    def __call__(self):
        return getattr(self, 'index.html')()

    @property
    def action(self):
        return self.request.form.get('zero-action', '.')

    def path_dispatch(self):
        """Dispatch non-Javascript to either waiver or assertion page."""

        self.request.response.redirect(self.request.form.get('zero-type',
                                                             'index.html'))

    def can_issue(self):
        """Inspect the request and see if we have enough information to
        issue an assertion/waiver."""

        # make sure they've confirmed their understanding
        confirm = self.request.form.get('confirm', False)
        
        return confirm is not False

    def get_html(self):
        """Return the HTML for the assertion/waiver as described by the
        query string."""

        return zero.Metadata()(self.request.form['license-uri'],
                               self.request.form)
