import email.Charset
email.Charset.add_charset('utf-8', email.Charset.SHORTEST, None, None)
from email.MIMEText import MIMEText
import re

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


class Sampling(grok.View):

    @property
    def target_lang(self):
        """Return the request language."""

        return self.request.locale.id.language
    
class Gpl(grok.View):
    grok.name('cc-gpl')

class Lgpl(grok.View):
    grok.name('cc-lgpl')

class Wiki(grok.View):

    def render(self):

        self.request.response.redirect(
            'results-one?license_code=by-sa&wiki=true')
    
class Music(grok.View):
    pass

class Xmp(Results):
    grok.name('xmp')
    grok.template('xmp')
    
    def _strip_href(self, input_str):
        """Take input_str and strip out the <a href='...'></a> tags."""

        return input_str
    
##         result = re.compile("""\<a .+ href=["'].+["']\>""", re.I).sub("", input_str)
##         result = re.compile("""</a>""", re.I).sub("", result)

##         return result
        
    def workType(self, format):

        WORK_FORMATS = {'Other':None,
                        'Audio':'Sound',
                        'Video':'MovingImage',
                        'Image':'StillImage',
                        'Interactive':'InteractiveResource'
                        }

        if format == "":
            return "work"

        if format not in WORK_FORMATS:
            return format

        return WORK_FORMATS[format] 

    def update(self):
        # assemble the necessary information for the XMP file before rendering

        year = ('field_year' in self.request.form and
                self.request['field_year']) or ""
        creator = ('field_creator' in self.request.form and
                   self.request['field_creator']) or None
        work_type = self.workType(('field_format' in self.request.form and
                              self.request['field_format']) or "")
        work_url = ('field_url' in self.request.form and
                    self.request['field_url']) or None

        # determine the license notice
        if ('publicdomain' in self.license.uri):
            notice = "This %s is dedicated to the public domain." % (work_type)
            copyrighted = False
        else:

            if creator:
                notice = "Copyright %s %s.  " % (year, creator,)
            else:
                notice = ""

            i18n_work = translate(cc.engine.i18n.I18N_DOMAIN, 'util.work')
            work_notice = self._strip_href(
                translate(cc.engine.i18n.I18N_DOMAIN,
                          'license.work_type_licensed',
                          mapping={'license_name':self.license.name,
                                   'license_url':self.license.uri,
                                   'work_type':i18n_work}
                ) )

            notice = notice + work_notice

            copyrighted = True

        self.xmp_info = {
            'copyrighted': copyrighted,
            'notice':notice,
            'license_url':self.license.uri,
            'license':self.license,
            'work_url':work_url
            }

    
    def render(self): 
        self.response.setHeader('Content-Type',
                                'application/xmp; charset=UTF-8')
        self.response.setHeader('Content-Disposition',
                                u'attachment; filename="CC_%s.xmp"' %
                                self.license.name.strip().replace(' ', '_'));


        # print the XMP
        return u"""<?xpacket begin='' id=''?><x:xmpmeta xmlns:x='adobe:ns:meta/'>
        <rdf:RDF xmlns:rdf='http://www.w3.org/1999/02/22-rdf-syntax-ns#'>

         <rdf:Description rdf:about=''
          xmlns:xapRights='http://ns.adobe.com/xap/1.0/rights/'>
          <xapRights:Marked>%(copyrighted)s</xapRights:Marked>""" % xmp_info
        if xmp_info['work_url'] != None:
            print """  <xapRights:WebStatement rdf:resource='%(work_url)s'/>""" % xmp_info
        print """ </rdf:Description>

         <rdf:Description rdf:about=''
          xmlns:dc='http://purl.org/dc/elements/1.1/'>
          <dc:rights>
           <rdf:Alt>
            <rdf:li xml:lang='x-default' >%(notice)s</rdf:li>
           </rdf:Alt>
          </dc:rights>
         </rdf:Description>

         <rdf:Description rdf:about=''
          xmlns:cc='http://creativecommons.org/ns#'>
          <cc:license rdf:resource='%(license_url)s'/>
         </rdf:Description>

        </rdf:RDF>
        </x:xmpmeta>
        <?xpacket end='r'?>
        """ % self.xmp_info

class NonWeb(Results):
    grok.name('non-web-popup')

class HtmlPopup(Results):
    grok.name('work-html-popup')
    
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
