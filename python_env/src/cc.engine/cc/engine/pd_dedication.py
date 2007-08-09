import grok

from cc.engine.chooser import LicenseEngine
    
class pd_information(grok.View):
    grok.name('publicdomain-2')
    grok.context(LicenseEngine)


class pd_waiting_verification(grok.View):
    grok.name('publicdomain-waiting-email-verification')
    grok.context(LicenseEngine)

    def update(self):
        """Send the PD verification email."""

        self.email_result = self.context.send_pd_confirmation(
            '/licenses/publicdomain-3',
            self.request.get('email', False),
            self.request.get('title', False),
            self.request.get('copyright_holder', False),
            )
        

class pd_confirm(grok.View):
    grok.name('publicdomain-3')
    grok.context(LicenseEngine)

    @property
    def hash_ok(self):
        """Verify the hash and return True or False."""

    
class pd_final(grok.View):
    grok.name('publicdomain-4')
    grok.context(LicenseEngine)

    def update(self):

        self.email_result = self.context.send_pd_dedication(
            self.request.get('email', False),
            self.request.get('title', False),
            self.request.get('copyright_holder', False),
            )

    
