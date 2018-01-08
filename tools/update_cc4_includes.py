#!/usr/bin/env python3
# Copyright 2017 Creative Commons
# Written by Affinity Bridge
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

import re, sys, re, getopt
from pathlib import Path

class UpdateLicenseCode(object):
    """Add common elements -- current Site Header, Site Footer, and Head
       statements -- to licence code HTML files."""

    placeholders = {\
        'head': ('<!-- Head Start - DO NOT DELETE -->', '<!-- Head End - DO NOT DELETE -->'), \
        'header': ('<!-- Site Header Start - DO NOT DELETE -->', '<!-- Site Header End - DO NOT DELETE -->'), \
        'footer': ('<!-- Site Footer Start - DO NOT DELETE -->', '<!-- Site Footer End - DO NOT DELETE -->') }

    def usage(self):
        print('')
        print('update_cc4_includes.py [-av]')
        print('  -v: Verbose output')
        print('')
        print('  e.g. update_cc4_includes.py')
        print('       update_cc4_includes.py -v')

    def log(self, message, type = 'standard'):
        if (type == 'standard') or (type == 'verbose' and self.verbose):
            print(message)

    def get_args(self):
        """Get arguments/options and set corresponding flags. On validation error
           print usage help"""
        try:
            opts, args = getopt.getopt(sys.argv[1:], "v")
        except getopt.GetoptError:
            self.usage()
            return False

        self.verbose = False
        for option in opts:
            if '-v' in option:
                self.verbose = True

        return True


    def get_path(self):
        """Where are the licenses?"""
        self.path = False
        path = Path.cwd()
        pathdir = path.name
        if pathdir == 'legalcode':
            self.path = path
        if pathdir == 'docroot':
            self.path = path / 'legalcode'
        if pathdir == 'tools':
            self.path = path.parent / 'docroot' /'legalcode'
        if not self.path:
            print('Please run from within the checked-out project.')
        if self.path:
            self.includes_path = Path(sys.path[0] + '/legalcode-includes')
        return self.path != False

    def process_files(self, filelist):
        """File processing loop"""
        for filepath in filelist:
            self.process_file(filepath)

    def process_file(self, filepath):
        """Verify the required placeholders exist and update file with common
           elements"""
        self.log("\n" + 'Processing: ' + filepath.name, 'verbose')
        with filepath.open(encoding='utf-8') as infile:
            content = infile.read()
            
        if self.has_placeholders(content):
            self.log('   Updating content: ' + filepath.name, 'verbose')
            content = self.add_includes(content)
            with filepath.open('w', encoding='utf-8') as outfile:
                outfile.write(content)
        else:
            self.log('   No placeholders, skipping: ' + filepath.name, 'standard')
            
        return

    def add_includes(self, content):
        """Add the appropriate includes"""
        for placeholder_pair in UpdateLicenseCode.placeholders:
            start, end = UpdateLicenseCode.placeholders[placeholder_pair]

            includefile = False
            if placeholder_pair == 'head':
                includefile = self.includes_path / 'html-head.html'
            elif placeholder_pair == 'header':
                includefile = self.includes_path / 'site-header.html'
            elif placeholder_pair == 'footer':
                includefile = self.includes_path / 'site-footer.html'
            if not includefile:
                continue

            with includefile.open() as infile:
                includetext = infile.read()

            replacement = start + "\n" + includetext + "\n" + end
            target_string = re.search(start + '.*?' + end, content, re.DOTALL).group()
            content = content.replace(target_string, replacement, 1)
            
        return content
        
    def has_placeholders(self, content):
        """Verify all of the required placeholders exist in a file"""
        for placeholder_pair in UpdateLicenseCode.placeholders:
            for placeholder in UpdateLicenseCode.placeholders[placeholder_pair]:
                if content.find(placeholder) == -1:
                    return False
        return True

    def main(self):
        """Get the command line arguments, find the files, and process them"""
        if self.get_args() and self.get_path():
            file_list = [f for f in self.path.glob('*4.0*.html')]
            self.process_files(file_list)

if __name__ == '__main__':
    updater = UpdateLicenseCode()
    updater.main()
