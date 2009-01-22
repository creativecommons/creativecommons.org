from zope.interface import implements
from interfaces import IRdfaGenerator

REASON_STRINGS = dict(
    us_govt = 'the work was created by the U.S. Government',
    us_1923 = 'the work was created in the U.S. before 1923',
    )

class Metadata(object):
    implements(IRdfaGenerator)

    IMAGE_BASE = "http://labs.creativecommons.org/zero/images"

    def __init__(self, license):
        self.license = license

    def _get_assertion_template(self, license_uri, work_data):
        """Return the template for use with an assertion.  Assumes 
        pre-processing of the actor and work strings."""

        work_data['action_predicate'] = 'assertedBy'

        # pull interesting bits out of the work_data mapping
        actor_href = work_data.get('actor_href', '').strip()
        actor = work_data.get('name', '').strip()

        # assemble the actor HTML
        if actor_href:
            if not actor:
                work_data['name'] = actor = actor_href

            actor = """<a rel="cc:%(action_predicate)s" href="%(actor_href)s">
<span about="%(actor_href)s" property="dc:title">%(name)s</span></a>""" % \
                work_data

        # assemble the work HTML
        no_title = False
        work_href = work_data.get('work_url', '').strip()
        work = work_data.get('work_title', False)
        if not work:
            no_title = True
            if not work_data.get('name', ''):
                work_data['work_title'] = 'This work'
                work = 'This work'
            else:
                work_data['work_title'] = 'this work'
                work = 'this work'

        if work_href:
            if not no_title:
                work = '<a href="%s"><span property="dc:title">%s</span></a>' % (work_href, work)
                pass
            else:
                work = '<a href="%s">%s</a>' % (work_href, work)
            
        work_data.update(dict(work=work, 
                              actor=actor,
                              IMAGE_BASE=self.IMAGE_BASE
                              )
                         )

        template = """<p xmlns:cc="http://creativecommons.org/ns#"
        xmlns:dc="http://purl.org/dc/elements/1.1/" rel="cc:licenseOffer">
  <a rel="license"
     href="%(license_uri)s" style="text-decoration:none;">
     <img src="%(IMAGE_BASE)s/88x31/cc-zero.png" border="0" alt="" />
  </a>
  <br/>"""

        if not actor:
            template +="""%(work)s """
        else:
            template +="""%(actor)s asserts %(work)s """

        template += """ is <a rel="license"
    href="%(license_uri)s">free
    of any copyrights</a>"""

        if work_data.get('assertion_reason', False):
            template += "; %(reasons)s"

            reasons = work_data.get('assertion_reason')
            if type(reasons) in (str, unicode):
                reasons = [reasons]

            reason_links = []
            for r in reasons:
                if r == 'other' and work_data.get('other_assertion_reason', False):
                    reason_links.append(
                        '<span property="cc:assertionBasis">%s</span>' %
                        work_data.get('other_assertion_reason')
                        )

                elif REASON_STRINGS.get(r, False):
                    # general case
                    reason_links.append('<a href="http://creativecommons.org/ns#%s"'
                                          ' rel="cc:assertionBasis">%s</a>' % (
                        r, REASON_STRINGS[r]))

            if len(reason_links) > 1:
                reason_links = ", ".join(reason_links[:-1]) + " and " + reason_links[-1]
            else:
                reason_links = reason_links[0]

            work_data.update(dict(reasons=reason_links))
            
        else:
            template += "."

        template += "</p>"

        return template % work_data

    def _get_waiver_template(self, license_uri, work_data):
        """Return the template for use with a waiver.  Assumes 
        pre-processing of the actor and work strings."""

        work_data['action_predicate'] = 'waivedBy'

        # pull interesting bits out of the work_data mapping
        actor_href = work_data.get('actor_href', '').strip()
        actor = work_data.get('name', '').strip()

        # assemble the actor HTML
        if actor_href:
            if not actor:
                work_data['name'] = actor = actor_href

            actor = """<a rel="cc:%(action_predicate)s" href="%(actor_href)s">
<span about="%(actor_href)s" property="dc:title">%(name)s</span></a>""" % \
                work_data

        # assemble the work HTML
        no_title = False
        work_href = work_data.get('work_url', '').strip()
        work = work_data.get('work_title', False)
        if not work:
            no_title = True
            work_data['work_title'] = 'this work'
            work = 'this work'

        if work_href:
            if no_title:
                work = '<a href="%s">%s</a>' % (work_href, work)
            else:
                work = '<a href="%s"><span property="dc:title">%s</span></a>' % (work_href, work)
                
        work_data.update(dict(work=work, 
                              actor=actor,
                              IMAGE_BASE=self.IMAGE_BASE
                              )
                         )

        template = """<p xmlns:cc="http://creativecommons.org/ns#"
        xmlns:dc="http://purl.org/dc/elements/1.1/" rel="cc:licenseOffer">
<a rel="license"
   href="%(license_uri)s" style="text-decoration:none;">
   <img src="%(IMAGE_BASE)s/88x31/cc-zero.png" border="0" alt="CC0" /></a>
<br/>"""

        if not actor:
            template += """The owner of %(work)s has """
        else:
            template += """To the extent possible under law, %(actor)s has """

        template += """<a rel="license"
      href="%(license_uri)s">waived</a> 
all copyright, moral rights, database rights, and any other rights that 
might be asserted over %(work)s.
</p>"""

        return template % work_data

    def with_form(self, work_data):
        """Return the HTML+RDFa for the license + work metadata for CC-0 
        licenses."""

        work_data['license_uri'] = self.license.uri

        if self.license.code == 'zero':
            return self._get_waiver_template(self.license.uri, work_data)
        else:
            return self._get_assertion_template(self.license.uri, work_data)



