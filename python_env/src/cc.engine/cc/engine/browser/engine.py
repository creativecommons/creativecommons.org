from zope.publisher.browser import BrowserPage
from zope.app.pagetemplate import ViewPageTemplateFile

import cc.license

class Index(BrowserPage):
    """License Engine index."""

    target_lang = ''

    def selected_jurisdiction(self):
        """Return the appropriate default jurisdiction -- either one explicitly
        requested by the user, or a good guess based on their language."""

        return "-"

    def license_class(self, class_name = cc.license.classes.STANDARD):

        return cc.license.LicenseFactory().get_class(class_name)
    
    def __call__(self):

        # determine if we're using the standard site or partner interface
        if u'partner' in self.request.form:
            return ViewPageTemplateFile('partner.pt')(self)

        else:
            return ViewPageTemplateFile('index.pt')(self)
        
        response = self.request.response
        response.setHeader('Content-Type', 'text/plain')
        return repr(self.context)

    
class Results(BrowserPage):

    @property
    def is_rtl(self):
        return False
    
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

    def __call__(self):

        return ViewPageTemplateFile('results.pt')(self)

