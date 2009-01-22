from zope.interface import implements
from interfaces import IRdfaGenerator

class Metadata(object):
    implements(IRdfaGenerator)

    def _get_assertion_template(self, actor, work):
        """Return the template for use with an assertion.  Assumes 
        pre-processing of the actor and work strings."""

        template = """<p about="%(work_url)s">
  <a rel="license"
     href="http://staging.creativecommons.org/licenses/zero/1.0/" style="text-decoration:none;">
     <img src="http://mirrors.creativecommons.org/zero/88x31/cc-zero.png" border="0" alt="" />
  </a>
  <br/>"""

        if not actor:
            template +="""%(work)s """
        else:
            template +="""%(actor)s asserts %(work)s """

        template += """ is <a rel="license"
    href="http://staging.creativecommons.org/licenses/zero/1.0/">free
    of any copyrights</a>. 
</p>"""

        return template

    def _get_waiver_template(self, actor, work):
        """Return the template for use with a waiver.  Assumes 
        pre-processing of the actor and work strings."""

        template = """<p about="%(work_url)s">
<a rel="license"
   href="http://staging.creativecommons.org/licenses/zero/1.0/" style="text-decoration:none;">
   <img src="http://mirrors.creativecommons.org/zero/80x31/cc-zero.png" border="0" alt="CC0" /></a>
<br/>"""

        if not actor:
            template += """The owner of %(work)s has """
        else:
            template += """To the extent possible under law, %(actor)s has """

        template += """<a rel="license"
      href="http://staging.creativecommons.org/licenses/zero/1.0/">waived</a> 
all copyright, moral rights, database rights, and any other rights that 
might be asserted over %(work)s. """

        return template

    def __call__(self, license_uri, work_data):
        """Return the HTML+RDFa for the license + work metadata for CC-0 
        licenses."""

        # pull interesting bits out of the work_data mapping
        actor_href = work_data.get('actor_href', '').strip()
        actor = work_data.get('name', '').strip()

        # assemble the actor HTML
        if actor_href:
            if not actor:
                work_data['name'] = actor = actor_href

            actor = """<a rel="cc:%(action_predicate)s" href="%(actor_href)s">
       <span about="%(actor_href)s" property="dc:title">%(name)s</span></a>"""

        # assemble the work HTML
        work_href = work_data.get('work_url', '').strip()
        work = work_data.get('work_title', 'this work')
        if not work: 
            work_data['work_title'] = 'this work'
            work = 'this work'

        if work_href:
            work = '<a href="%s">%s</a>' % (work_href, work)
            
        work_data.update(dict(work=work, 
                              actor=actor,
                              )
                         )

        if work_data['confirm'] == 'assertion':
            work_data['action_predicate'] = 'assertedBy'
            template = self._get_assertion_template(actor, work)
        else:
            work_data['action_predicate'] = 'waivedBy'
            template = self._get_waiver_template(actor, work)

        return (template % work_data) % work_data


