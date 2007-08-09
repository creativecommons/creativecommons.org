from datetime import datetime
from urllib import urlencode

import grok
from zope.interface import implements

import zope.component
from zope.component import getUtility
from zope.sendmail.interfaces import IMailDelivery
from zope.i18n import translate

import cc.license

import cc.engine.i18n
from cc.engine.interfaces import ILicenseEngine, IDefaultJurisdiction

class LicenseEngine(grok.Application, grok.Container):
    """LicenseEngine Application Class

    Provides web-application specific assistance (non-presentation) for the
    license engine.
    """
    implements(ILicenseEngine)

    def generate_hash(self, email_addr, title, holder):
        return str(hash((email_addr, title, holder)))
    
    def send_pd_confirmation(self, next_url, email_addr, title, holder):
        """Sends the confirmation email to the PD dedicator."""

        mhost = getUtility(IMailDelivery, 'cc_engine')
        lang = 'en' # YYY context.getLanguage()

        if False in (email_addr, title, holder):
            return False

        nextstep_qs = {'lang':lang,
                       'license_code':'publicdomain',
                       'title':title,
                       'copyright_holder':holder,
                       'email':email_addr,
                       'hash':self.generate_hash(email_addr, title, holder),
                      }
        nextstep_url = "%s?%s" % (next_url, urlencode(nextstep_qs))

        message = "To: %s\n" \
                  "From: info@creativecommons.org\n" \
                  "Subject: Confirm your Public Domain Dedication at Creative Commons\n" \
                  "\n%s" % (
            email_addr,
            translate(domain=cc.engine.i18n.I18N_DOMAIN,
                      msgid='license.pd_confirmation_email',
                      mapping={'title':title,
                               'clickthrough_url':nextstep_url, },
                      target_language=lang)
            )

        mhost.send('info@creativecommons.org', (email_addr,), message)

        return True

    def send_pd_dedication(self, email_addr, title, holder):
        """Send the public domain dedication after confirmation."""

        mhost = getUtility(IMailDelivery, 'cc_engine')
        lang = "en" # YYY context.getLanguage()

        message = "To: %s\n" \
                  "From: info@creativecommons.org\n" \
                  "Subject: Creative Commons - Public Domain Dedication\n" \
                  "\n%s" % (
            email_addr,
            translate(domain=cc.engine.i18n.I18N_DOMAIN,
                      msgid='license.pd_dedication_email',
                      mapping={'title':title,
                               'email':email_addr,
                               'copyright_holder':holder,
                               'sysdate':datetime.now().strftime("%B %d, %Y")},
                      target_language=lang)
            )

        mhost.send('info@creativecommons.org', (email_addr,), message)

        return True
        

class BaseIndexViewMixin(object):

    target_lang = ''

    @property
    def is_rtl(self):
        """Return 'rtl' if the request locale is represented right-to-left;
        otherwise return an empty string."""

        if self.request.locale.orientation.characters == u'right-to-left':
            return 'rtl'

        return ''

    @property
    def is_rtl_align(self):
        """Return the appropriate alignment for the request locale:
        'right' or 'left'."""

        return self.request.locale.orientation.characters.split('-')[0]

    def selected_jurisdiction(self):
        """Return the appropriate default jurisdiction -- either one explicitly
        requested by the user, or a good guess based on their language."""

        # Delegate to an adapter
        return IDefaultJurisdiction(self.request).getJurisdictionId()
    
    def license_class(self, class_name = cc.license.classes.STANDARD):

        return cc.license.LicenseFactory().get_class(class_name)
    
class Partner(grok.View, BaseIndexViewMixin):
    """Partner UI index view."""
    grok.context(LicenseEngine)

class Cc_Index(grok.View, BaseIndexViewMixin):
    """cc.org License Engine UI."""
    grok.context(LicenseEngine)
    
class Index(grok.View):
    """License Engine index."""
    grok.context(LicenseEngine)

    def render(self):

        # determine if we're using the standard site or partner interface
        if u'partner' in self.request.form:
            return Partner(self.context, self.request)()

        else:

            return Cc_Index(self.context, self.request)()

    
class Results(grok.View):
    grok.name('results-one')
    grok.context(LicenseEngine)

    template = 'engine_templates/results.pt'

    @property
    def is_rtl(self):
        """Return 'rtl' if the request locale is represented right-to-left;
        otherwise return an empty string."""

        if self.request.locale.orientation.characters == u'right-to-left':
            return 'rtl'

        return ''

    @property
    def is_rtl_align(self):
        """Return the appropriate alignment for the request locale:
        'right' or 'left'."""

        return self.request.locale.orientation.characters.split('-')[0]

    def _work_info(self, request):
        """Extract work information from the request and return it as a
        dict."""

        # XXX
        return {}
    
    def _issue(self, request=None):
        """Extract the license engine fields from the request and return a
        License object."""

	if request is None:
	    request = self.request

	jurisdiction = ''
	locale = request.get('lang', '')
	code = ''

        license_class = 'standard'
        answers = {}
        
	if request.has_key('pd') or request.has_key('publicdomain') or (request.has_key('license_code') and request['license_code'] == 'publicdomain'):
	   # this is public domain
           license_class = 'publicdomain'

	# check for license_code
	elif request.has_key('license_code'):
	   jurisdiction = (('jurisdiction' in request.keys()) and
                           (request['jurisdiction'])) or \
                          (('field_jurisdiction' in request.keys()) and
                           (request['field_jurisdiction'])) or \
			  ''
           license_class, answers = cc.license.support.expandLicenseCode(
               request['license_code'],
               jurisdiction = jurisdiction,
               locale=locale,
               version = request.form.get('version', None)
               )

	else:
	   jurisdiction = ('field_jurisdiction' in request.keys() and request['field_jurisdiction']) or jurisdiction

           answers.update(dict(
               jurisdiction = ('field_jurisdiction' in request.keys() and request['field_jurisdiction']) or jurisdiction,
               commercial = request['field_commercial'],
               derivatives = request['field_derivatives'],
               )
                          )

           if request.form.get('version', False):
               answers['version'] = request['version']

        # add the work to the answers block
        answers.update(self._work_info(request))

	# return the license object
        return cc.license.LicenseFactory().get_class(license_class).issue(
            **answers)
        
    @property
    def license(self):

        if not(hasattr(self, '_license')):
            self._license = self._issue()

            # browser-specific information injection
            # XXX we should probably adapt to something like IBrowserLicense
            self._license.slim_image = self._license.imageurl.replace(
                '88x31','80x15')
            
        return self._license


