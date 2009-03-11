
import zope.app.locales.extract

# add a new name for POTEntry
zope.app.locales.extract.POTEntry_base = zope.app.locales.extract.POTEntry

class CC_POTEntry(zope.app.locales.extract.POTEntry_base):
    """CC specific version of POTEntry."""

    def write(self, file):
        """Write the entry to the file; if a default exists, write it 
        as the msgstr."""

        if self.comments:
            file.write(self.comments)
        file.write('msgid %s\n' % normalize(self.msgid))
        file.write('msgstr ""\n')
        if (isinstance(self.msgid, Message) and
            self.msgid.default is not None):
            default = self.msgid.default.strip()
            lines = normalize(default).split("\n")
            #lines[0] = "#. Default: %s\n" % lines[0]
            #for i in range(1, len(lines)):
            #    lines[i] = "#.  %s\n" % lines[i]
            for line in lines:
                file.write(line)
                file.write('\n')

            #file.write("".join(lines))
            file.write('\n')

        file.write('\n')

zope.app.locales.extract.POTEntry = CC_POTEntry

from zope.app.locales.extract import *
