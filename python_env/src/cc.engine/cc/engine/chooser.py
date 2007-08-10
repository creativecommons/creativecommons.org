import email.Charset
email.Charset.add_charset('utf-8', email.Charset.SHORTEST, None, None)
from email.MIMEText import MIMEText
import re

from datetime import datetime
from urllib import urlencode
from urlparse import urlparse

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

    def _work_info(self, request):
        """Extract work information from the request and return it as a
        dict."""

	result = {'title' : None,
		  'creator' : None,
		  'copyright_holder' : None,
		  'copyright_year' : None,
		  'description' : None,
		  'format' : None,
		  'work_url' : None,
		  'source_work_url' : None,
                  'source_work_domain' : None,
                  'attribution_name' : None,
                  'attribution_url' : None,
                  'more_permissions_url' : None,
		  }

	# look for keys that match the param names
	for key in request.form:
	    if key in result:
	        result[key] = request[key]

	# look for keys from the license chooser interface

	# work title
	if request.has_key('field_worktitle'):
	    result['title'] = request['field_worktitle']

	# creator
	if request.has_key('field_creator'):
	    result['creator'] = request['field_creator']

	# copyright holder
	if request.has_key('field_copyrightholder'):
	    result['copyright_holder'] = result['holder'] = \
                request['field_copyrightholder']

	# copyright year
	if request.has_key('field_year'):
	    result['copyright_year'] = result['year'] = request['field_year']

	# description
	if request.has_key('field_description'):
	    result['description'] = request['field_description']

	# format
	if request.has_key('field_format'):
	    result['format'] = result['type'] = request['field_format']

	# source url
	if request.has_key('field_sourceurl'):
	    result['source_work_url'] = result['source-url'] = \
                request['field_sourceurl']

            # extract the domain from the URL
            result['source_work_domain'] = urlparse(
                result['source_work_url'])[1]

            if not(result['source_work_domain'].strip()):
                result['source_work_domain'] = result['source_work_url']

        # attribution name
        if request.has_key('field_attribute_to_name'):
            result['attribution_name'] = request['field_attribute_to_name']

        # attribution URL
        if request.has_key('field_attribute_to_url'):
            result['attribution_url'] = request['field_attribute_to_url']

        # more permissions URL
        if request.has_key('field_morepermissionsurl'):
            result['more_permissions_url'] = request['field_morepermissionsurl']

	return result

    def license_class(self, class_name = cc.license.classes.STANDARD):

        return cc.license.LicenseFactory().get_class(class_name)

    def issue(self, request):
        """Extract the license engine fields from the request and return a
        License object."""

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

    @property
    def target_lang(self):
        """Return the request language."""

        return self.request.locale.id.language
    
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

    @property
    def license(self):

        if not(hasattr(self, '_license')):
            self._license = self.context.issue(self.request)

            # browser-specific information injection
            # XXX we should probably adapt to something like IBrowserLicense
            self._license.slim_image = self._license.imageurl.replace(
                '88x31','80x15')
            
        return self._license


class Sampling(grok.View):

    @property
    def target_lang(self):
        """Return the request language."""

        return self.request.locale.id.language
    
class Wiki(grok.View):

    def render(self):

        self.request.response.redirect(
            'results-one?license_code=by-sa&wiki=true')
        
class EmailHtml(grok.View):
    grok.name('work-email')

    def update(self):
        """Email the license HTML to the user."""

        mhost = getUtility(IMailDelivery, 'cc_engine')

        email_addr = self.request.get('to_email', '')
        work_title = self.request.get('work_title', '')
        license_name = self.request.get('license_name')
        license_html = self.request.get('license_html')

        message_body = u"""

Thank you for using a Creative Commons License for your work "%s"

You have selected the %s License. You should include a
reference to this license on the web page that includes the work in question.

Here is the suggested HTML:

%s

Further tips for using the supplied HTML and RDF are here:
http://creativecommons.org/learn/technology/usingmarkup

Thank you!
Creative Commons Support
info@creativecommons.org
""" % (work_title, license_name, license_html)

        message = MIMEText(message_body.encode('utf-8'), 'plain', 'utf-8')
        message['Subject'] = 'Your Creative Commons License Information'
        message['From'] = 'info@creativecommons.org'
        message['To'] = email_addr

        mhost.send('info@creativecommons.org', (email_addr,), message)
