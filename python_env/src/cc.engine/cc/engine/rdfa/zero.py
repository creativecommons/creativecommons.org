from cc.engine.chooser import ResultsView
from cc.engine.licenses.zero import Zed

class Get(ResultsView):

    @property
    def license(self):

        # XXX This is a demo; it *always* returns CC0
        return Zed()

    def __call__(self):
        """Return the HTML+RDFa for the license + work metadata."""

        self.request.response.setHeader(
            'Content-Type', 'text/html; charset=UTF-8')

        if self.request.form['confirm'] == 'assertion':
            template = """<p about="%(work_url)s">
      <a rel="license"
         href="http://staging.creativecommons.org/licenses/zero/1.0/" style="text-decoration:none;">
         <img src="http://mirrors.creativecommons.org/zero/88x31/cc-zero.png" border="0" alt="" />
      </a>
      <br/>
      <a rel="cc:assertor" href="%(url)s">
	<span about="%(url)s" property="dc:title">%(name)s</span></a> asserts that 
      <span property="dc:title">%(work_title)s</span> is <a rel="license"
	href="http://staging.creativecommons.org/licenses/zero/1.0/">free
	of any copyrights</a>. 
    </p>"""

        else:
            template = """<p about="%(work_url)s">
<a rel="license"
   href="http://staging.creativecommons.org/licenses/zero/1.0/" style="text-decoration:none;">
   <img src="http://mirrors.creativecommons.org/zero/80x15/cc-zero.png" border="0" alt="" /></a>
<br/>
<a rel="cc:dedicator" href="%(url)s">
   <span about="%(url)s" 
	 property="dc:title">%(name)s</span></a> has dedicated 
      <a property="dc:title">%(work_title)s</a> to be <a rel="license"
      href="http://staging.creativecommons.org/licenses/zero/1.0/">free 
      of any legal obligations whatsoever</a>. To the 
      extent possible under the law, %(name)s waives all
      copyright, moral right, database rights, and any other rights
      that might be asserted over %(work_title)s.  
</p>""" 


        return template % self.request.form


