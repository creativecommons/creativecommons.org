from email.MIMEText import MIMEText
from zope.sendmail.interfaces import IMailDelivery

from cc.engine.chooser import BaseBrowserView as BrowserPage
from cc.engine.chooser import ResultsView

from cc.engine.rdfa import zero
from zope import component

from cc.engine.rdfa.interfaces import IRdfaGenerator
from cc.license.decorators import memoized
from cc.licenze.interfaces import ILicenseSelector

from cc.engine.support.iso3166 import IIso3166

class ZeroChooser(BrowserPage):

    def __call__(self):
        return getattr(self, 'index.html')()

    @property
    def action(self):
        return self.request.form.get('zero-action', '.')

    def country_list(self):
        """Return a sequence of two-tuples containing a country code 
        and name."""

        return component.getUtility(IIso3166).country_list()

    def can_issue(self):
        """Inspect the request and see if we have enough information to
        issue a waiver."""

        # make sure they've confirmed their understanding
        confirm = self.request.form.get('confirm', False)
        understand = self.request.form.get('understand', False)
        accept = self.request.form.get('waiver-affirm', False) and \
            self.request.form.get('waiver-decline', True)

        return (confirm and understand and accept)

    def issue(self):

        # we don't have a license URI; assume we need to issue
        license_class = self.request.form.get('license-class', None)

        if license_class is None:
            raise Exception()

        license = component.getUtility(ILicenseSelector, license_class).\
            process_form(self.request.form)

        return license

    license = property(issue)

    def rdfa(self):

        return IRdfaGenerator(self.issue()).format(self.request.form)


    def _send_html(self):

        mhost = component.getUtility(IMailDelivery, 'cc_engine')

        email_addr = self.request.form.get('email', '')
        license_name = self.license.name
        license_html = self.rdfa()

        message_body = u"""

Thank you for using a Creative Commons License for your work.

You have selected %s. You should include a reference to this
license on the web page that includes the work in question.

Here is the suggested HTML:

%s

Further tips for using the supplied HTML and RDF are here:
http://creativecommons.org/learn/technology/usingmarkup

Thank you!
Creative Commons Support
info@creativecommons.org
""" % (license_name, license_html)

        message = MIMEText(message_body.encode('utf-8'), 'plain', 'utf-8')
        message['Subject'] = 'Your Creative Commons License Information'
        message['From'] = 'info@creativecommons.org'
        message['To'] = email_addr

        mhost.send('info@creativecommons.org', (email_addr,),
                   message.as_string())

    def _send_subscription(self):
        """Send a subscription request."""

        SUBSCRIBE_ADDR = "cc-zero-announce-request@lists.ibiblio.org"

        mhost = component.getUtility(IMailDelivery, 'cc_engine')

        email_addr = self.request.form.get('email', '')
        message = MIMEText("", "plain", "utf-8")
        message['From'] = email_addr
        message['To'] = SUBSCRIBE_ADDR
        message['Subject'] = 'subscribe'

        mhost.send(email_addr, (SUBSCRIBE_ADDR,), message.as_string())

    def email_requested(self):

        return 'email' in self.request.form

    def send_email(self):

        try:
            self._send_html()

            if self.request.form.get('send_updates', False):
                self._send_subscription()
        except:
            return False

        return True

