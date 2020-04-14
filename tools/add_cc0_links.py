#!/usr/bin/env python3
# Copyright 2016, 2017 Creative Commons
# Written by Rob Myers <rob@creativecommons.org>
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

from pathlib import Path
import os.path
import re
import sys


class AddCC0Links(object):
    def usage(self):
        print("add-cc0-links.py LANGUAGE_CODE LANGUAGE_NAME")
        print("    e.g. add-cc0-links.py nl Nederlands")
        print(
            "    LANGUAGE_CODE must be 2 letters or 2-hyphen-N,"
            " the same used in filename."
        )
        print("    LANGUAGE_NAME must be in the relevant language")
        print(
            "                  if it contains whitespace, enclose in quotes."
        )

    def get_args(self):
        # Make sure there are enough args
        # Make sure arg 2 is a language code
        # Make sure arg 3 is not a language code
        self.args_ok = (
            (len(sys.argv) == 3)
            and (len(sys.argv[1]) >= 2)
            and (len(sys.argv[2]) >= 2)
        )
        if self.args_ok:
            self.language_code = sys.argv[1]
            self.language_name = sys.argv[2]
            self.exclude_pattern = "zero_1.0_" + self.language_code + ".html"
        else:
            self.usage()
        return self.args_ok

    def get_path(self):
        """Where are the licenses?"""
        self.path = False
        path = Path.cwd()
        pathdir = path.name
        if pathdir == "legalcode":
            self.path = path
        if pathdir == "docroot":
            self.path = path / "legalcode"
        if pathdir == "tools":
            self.path = path.parent / "docroot" / "legalcode"
        if not self.path:
            print("Please run from within the checked-out project.")
        return self.path is not False

    def get_files(self):
        """Get all the CC0 files *except* those we are linking to"""
        self.files = [
            f
            for f in self.path.glob("zero_1.0*.html")
            if not os.path.islink(f) and not f.match(self.exclude_pattern)
        ]
        self.files.sort()

    def process_files(self):
        """Add links to all the license files"""
        for filepath in self.files:
            self.process_file(filepath)

    def file_license_and_language(self, filepath):
        """Get the license number and language code from the file path"""
        elements = filepath.stem.split("_")
        # Un-translated deeds don't have a language code, so set to English
        if len(elements) != 3:
            elements += ["en"]
        return elements[0], elements[2]

    def links_in_page(self, content):
        """Find the translated license links at the bottom of the page"""
        return re.findall(
            r"//creativecommons\.org/publicdomain/zero/1\.0/"
            'legalcode([.][^"]{2,})?">([^>]+)</a>',
            content,
        )

    def is_rtl(self, content):
        """Determine whether the page is in a right-to-left script"""
        return re.search(r' dir="rtl"', content) is not None

    def insert_at_index(self, links, rtl):
        """Find the alphabetic position in the list of translated license links
           to insert the link at"""
        index = -1
        for match in links:
            if self.language_name.casefold() < match[1].casefold():
                break
            else:
                index += 1
        if rtl and index != -1:
            index -= 1
        return index

    def insert_link(self, content, lic, links, index):
        """Insert the link to the correct version of the license
           in the correct position in the list of links at the bottom of the
           page"""
        link = (
            '<a href="//creativecommons.org/publicdomain/zero/1.0/legalcode.'
            + self.language_code
            + '">'
            + self.language_name
            + "</a>"
        )
        if index == -1:
            target = '<a href="//creativecommons.org/publicdomain/zero/1.0/'
            replace = link + ", " + target
        else:
            lang = links[index][1]
            target = ">" + lang + "</a>"
            replace = target + ", " + link
        return content.replace(target, replace, 1)

    def file_contains_link_already(self, links):
        """Did we already add a link to this page?"""
        return (
            next(
                (
                    code
                    for code, name in links
                    if name == self.language_name or code == self.language_code
                ),
                False,
            )
            is not False
        )

    def process_file(self, filepath):
        """Get the file's details and insert a link to the translated version
           into it"""
        lic, lang = self.file_license_and_language(filepath)
        with filepath.open() as infile:
            content = infile.read()
        links = self.links_in_page(content)
        if not self.file_contains_link_already(links):
            rtl = self.is_rtl(content)
            index = self.insert_at_index(links, rtl)
            print(filepath)
            print(links)
            print(index)
            print(links[index])
            updated_content = self.insert_link(content, lic, links, index)
            with filepath.open("w") as outfile:
                outfile.write(updated_content)
            print("Added link to file: " + filepath.name)
        # else:
        #    print("File already contains link: " + filepath.name)

    def main(self):
        """Get the command line arguments, find the files, and process them"""
        if self.get_args() and self.get_path():
            self.get_files()
            self.process_files()


if __name__ == "__main__":
    link_adder = AddCC0Links()
    link_adder.main()
