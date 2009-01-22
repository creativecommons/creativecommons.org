import os
#from cc.license._lib.interfaces import ILicenseFormatter
#from cc.license._lib.exceptions import CCLicenseError
import zope.interface
import zope.component
from genshi.template import TemplateLoader
#from filters import Source, Permissions

from cc.engine.support.iso3166 import IIso3166
from interfaces import IRdfaGenerator

# template loader, which is reused in a few places
LOADER = TemplateLoader(
             os.path.join(os.path.dirname(__file__), 'templates'),
             auto_reload=False)

DEFAULT_PUBLISHER = "[_:publisher]"

class Country:

    def __init__(self, work_dict=None):
        if work_dict is None:
            work_dict = {}
        self.id = 'work_jurisdiction'
        if work_dict.get(self.id, None) not in ('', '-', None, False):
            self.activate = True
            self.source = work_dict[self.id]

            # only load template if we need to
            self.tmpl = LOADER.load('zero/country.xml')

            # get the additional information we need --
            # country name
            country_name = zope.component.getUtility(IIso3166)[self.source]

            # publisher URI
            publisher = work_dict.get('actor_href', DEFAULT_PUBLISHER)

            self.source_stream = self.tmpl.generate(country=self.source,
                                                    country_name=country_name,
                                                    publisher=publisher)
        else:
            self.activate = False

    def __call__(self, stream):

        for kind, data, pos in stream:

            if kind == 'END' and data == 'p':
                # this is the end of the paragraph; inject our contents
                if self.activate:
                    for local_kind, local_data, local_pos in self.source_stream:
                        yield local_kind, local_data, local_pos

            yield kind, data, pos

class HTMLFormatter(object):
    # zope.interface.implements(ILicenseFormatter)
    zope.interface.implements(IRdfaGenerator)

    def __init__(self, license):
        self.license = license

    @property
    def id(self):
        return 'html+rdfa'

    @property
    def title(self):
        return "HTML + RDFa formatter"

    def format(self, work_data={}, locale='en'):
        """Return an HTML + RDFa string serialization for the license,
            optionally incorporating the work metadata and locale."""

        template = 'default'
        kwargs = {}
        w = work_data # alias work_data for brevity

        # determine how we're referring to the work
        no_title = False
        work = work_data.get('work_title', False)

        if work:
            template = 'work'
                        
        # determine if we have actor information
        actor_href = work_data.get('actor_href', '').strip()
        actor = work_data.get('name', '').strip()

        if actor or actor_href:
            template = '%s-actor' % template

            # assemble the actor HTML
            if actor_href:
                if actor:
                    # href and name
                    work_data['actor'] = """<a href="%(actor_href)s" rel="dct:publisher"><span property="dct:title">%(name)s</span></a>""" % work_data
                else:
                    # href, no name
                    work_data['actor'] = """<a href="%(actor_href)s" rel="dct:publisher">%(actor_href)s</a>""" % work_data
            
            else:
                # no actor href -- use a bnode
                work_data['actor_href'] = DEFAULT_PUBLISHER
                work_data['actor'] = """<span rel="dct:publisher" resource="%(actor_href)s"><span property="dct:title">%(name)s</span></span>""" % work_data


        # pack kwargs
        kwargs['form'] = w
        kwargs['license'] = self.license
        kwargs['locale'] = locale
        
        template = "%s/%s.xml" % (self.license.license_class, template)
        self.tmpl = LOADER.load(template)
        stream = self.tmpl.generate(**kwargs)
        stream = stream | Country(work_data)
        return stream.render('xhtml')
