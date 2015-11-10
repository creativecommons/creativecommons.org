from zope.app.locales.extract import POTMaker, POTEntry
from zope.app.locales.extract import py_strings
from zope.app.locales.extract import tal_strings
from babel.messages.pofile import normalize
from zope.i18nmessageid import Message

from zope.app.locales.pygettext import make_escapes
make_escapes(1)

class CCPOTEntry(POTEntry):
    def write(self, file):
        if (isinstance(self.msgid, Message) and
            self.msgid.default is not None):

            # Write Default
            default = self.msgid.default.strip()
            lines = normalize(default).split("\n")
            lines[0] = "#. Default: %s\n" % lines[0]
            for i in range(1, len(lines)):
                lines[i] = "#. %s\n" % lines[i]
            file.write("".join(lines))

        if self.comments:
            file.write(self.comments)
        file.write('msgid %s\n' % normalize(self.msgid))

        if (isinstance(self.msgid, Message) and
            self.msgid.default is not None):
            # Write msgstr
            file.write('msgstr %s\n' % normalize(default))
        else:
            file.write('msgstr ""\n')

        file.write('\n')


class CCPOTMaker(POTMaker):
    def add(self, strings, base_dir=None):
        for msgid, locations in strings.items():
            if msgid == '':
                continue
            if msgid not in self.catalog:
                self.catalog[msgid] = CCPOTEntry(msgid)

            for filename, lineno in locations:
                if base_dir is not None:
                    filename = filename.replace(base_dir, '')
                self.catalog[msgid].addLocationComment(filename, lineno)


maker = CCPOTMaker('extracted.pot', '')
maker.add(py_strings('cc/engine/', 'cc_org'),
          'cc/engine/')
maker.add(tal_strings('cc/engine/templates/', 'cc_org', include_default_domain=True),
          'cc/engine/templates/')
maker.write()
