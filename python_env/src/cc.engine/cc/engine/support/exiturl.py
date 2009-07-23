from zope.interface import Interface, implements
from urllib import quote, unquote_plus
from urlparse import urlparse, urljoin

class IExiturlGenerator(Interface):
    def exit_url(url, referrer, license):
        pass

class ExiturlGenerator:
    
    implements(IExiturlGenerator)

    def exit_url(self, url, referrer, license):
        
        url = unquote_plus(url)
 
        # test if the exit_url is an absolute uri
        if urlparse(url).scheme not in ['http', 'https']:
            
            # this will accomodate only for 'valid' relative paths
            # e.g. foo/bar.php or /foo/bar.php?id=1, etc.
            url = urljoin(referrer, url)

        url = url.replace('[license_url]', quote(license.uri))
        url = url.replace('[license_name]', quote(license.name))
        url = url.replace('[license_button]', quote(license.imageurl))
        url = url.replace('[deed_url]', quote(license.uri))
        
        return url
