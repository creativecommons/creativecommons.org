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

# Standard library
from pathlib import Path
import getopt
import os
import re
import sys

# Local/library specific
import lang_tag_to


class UpdateLicenseCode(object):
    """Add common elements -- current Site Header, Site Footer, and Head
       statements -- to licence code HTML files."""

    placeholders = {
        "head": (
            "<!-- Head Start - DO NOT DELETE -->",
            "<!-- Head End - DO NOT DELETE -->",
        ),
        "header": (
            "<!-- Site Header Start - DO NOT DELETE -->",
            "<!-- Site Header End - DO NOT DELETE -->",
        ),
        "footer": (
            "<!-- Site Footer Start - DO NOT DELETE -->",
            "<!-- Site Footer End - DO NOT DELETE -->",
        ),
        "language-selector": (
            "<!-- Language Selector Start - DO NOT DELETE -->",
            "<!-- Language Selector End - DO NOT DELETE -->",
        ),
        "language-footer": (
            "<!-- Language Footer Start - DO NOT DELETE -->",
            "<!-- Language Footer End - DO NOT DELETE -->",
        ),
    }
    languages = {}
    license_data = {}
    license_types = []
    iso_to_language = lang_tag_to.LABEL
    lang_sel_text = lang_tag_to.SELECT_TEXT

    def usage(self):
        print("")
        print("update_cc4_includes.py [-av]")
        print("  -v: Verbose output")
        print("")
        print("  e.g. update_cc4_includes.py")
        print("       update_cc4_includes.py -v")

    def log(self, message, type="standard"):
        if (type == "standard") or (type == "verbose" and self.verbose):
            print(message)

    def get_args(self):
        """Get arguments/options and set corresponding flags. On validation
           error print usage help"""
        try:
            opts, args = getopt.getopt(sys.argv[1:], "v")
        except getopt.GetoptError:
            self.usage()
            return False

        self.verbose = False
        for option in opts:
            if "-v" in option:
                self.verbose = True

        return True

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
        if self.path:
            self.includes_path = Path(f"{sys.path[0]}/legalcode-includes")
        return self.path is not False

    def process_files(self, filelist):
        """File processing loop"""
        languages = {}
        license_types = []
        # pre-process
        for filepath in filelist:
            license_data = self.parse_filename(filepath)
            type_ = license_data["type"]
            license_types.append(type_)
            if type_ not in languages:
                languages[type_] = []
            languages[type_].append(license_data["language"])
            self.license_data[filepath] = license_data
        # sort and store data
        self.license_types = sorted(list(set(license_types)))
        for type_ in self.license_types:
            self.languages[type_] = []
            self.languages[type_] = sorted(list(set(languages[type_])))
        # process files
        for filepath in filelist:
            self.process_file(filepath)

    def process_file(self, filepath):
        """Verify the required placeholders exist and update file with common
           elements"""
        self.log(f"\nProcessing: {filepath.name}", "verbose")
        with filepath.open(encoding="utf-8") as infile:
            content = infile.read()

        if self.has_placeholders(content):
            self.log(f"   Updating content: {filepath.name}", "verbose")
            content = self.add_includes(content)
            content = self.add_language_selector(content, filepath)
            content = self.add_language_footer(content, filepath)
            with filepath.open("w", encoding="utf-8") as outfile:
                outfile.write(content)
        else:
            self.log(
                f"   No placeholders, skipping: {filepath.name}", "standard"
            )

        return

    def add_includes(self, content):
        """Add the appropriate includes"""
        for placeholder_pair in UpdateLicenseCode.placeholders:
            start, end = UpdateLicenseCode.placeholders[placeholder_pair]

            includefile = False
            if placeholder_pair == "head":
                includefile = self.includes_path / "html-head.html"
            elif placeholder_pair == "header":
                includefile = self.includes_path / "site-header.html"
            elif placeholder_pair == "footer":
                includefile = self.includes_path / "site-footer.html"
            if not includefile:
                continue

            with includefile.open() as infile:
                includetext = infile.read()

            replacement = f"{start}\n{includetext}\n{end}"
            target_string = re.search(
                f"{start}.*?{end}", content, re.DOTALL
            ).group()
            content = content.replace(target_string, replacement, 1)

        return content

    def add_language_selector(self, content, filepath):
        """Build and insert a language selector dropdown list."""
        license_data = self.license_data[filepath]
        current_language = license_data["language"]
        sibling_languages = self.languages[license_data["type"]]

        selector = (
            '<div id="language-selector-block" class="container">'
            '\n<div class="language-selector-inner">'
            f"\n{self.lang_sel_text[current_language]}"
            '\n<img class="language-icon"'
            ' src="/images/language_icon_x2.png" alt="Languages">'
            "\n<select>"
        )
        for iso_code in sibling_languages:
            # Set the selected option to the current language of the page
            selected = ""
            if iso_code == current_language:
                selected = ' selected="selected"'
            # Determine to option value for the language. English breaks the
            # pattern so handle it differently.
            option_value = f"legalcode.{iso_code}"
            if iso_code == "en":
                option_value = "legalcode"
            # Add the selector vlaue
            selector = (
                f'{selector}\n<option value="{option_value}"{selected}>'
                f"{self.iso_to_language[iso_code]}"
                "</option>"
            )
        selector = f"{selector}\n</select>\n</div>\n</div>"

        # Add the language selector block to the content
        start, end = UpdateLicenseCode.placeholders["language-selector"]
        target_string = re.search(
            f"{start}.*?{end}", content, re.DOTALL
        ).group()
        replacement = f"{start}\n{selector}\n{end}"
        content = content.replace(target_string, replacement, 1)

        return content

    def add_language_footer(self, content, filepath):
        """Build and insert a language footer dropdown list."""
        license_data = self.license_data[filepath]
        current_language = license_data["language"]
        sibling_languages = self.languages[license_data["type"]]
        footer = ""
        for i, iso_code in enumerate(sibling_languages):
            if iso_code == current_language:
                continue
            # Determine to option value for the language. English breaks the
            # pattern so handle it differently.
            index = f"legalcode.{iso_code}"
            if iso_code == "en":
                index = "legalcode"
            link = (
                f'<a href="/licenses/{license_data["type"]}/4.0/{index}">'
                f"{self.iso_to_language[iso_code]}</a>,\n"
            )
            footer = f"{footer}{link}"
        footer = footer.rstrip(",\n")

        # Add the language footer block to the content
        start, end = UpdateLicenseCode.placeholders["language-footer"]
        target_string = re.search(
            f"{start}.*?{end}", content, re.DOTALL
        ).group()
        if current_language in ["ja", "zh-Hans", "zh-Hant"]:
            # Use ideographic full stop ("。")
            period = "\u3002"
        else:
            # Use ASCII period
            period = "."
        replacement = f"{start}\n{footer}{period}\n{end}"
        content = content.replace(target_string, replacement, 1)

        return content

    def parse_filename(self, filepath):
        license_info = filepath.name[0:-5].split("_")
        type = license_info[0]
        version = license_info[1]
        if len(license_info) > 2:
            language = license_info[2]
        else:
            language = "en"
        return {"type": type, "version": version, "language": language}

    def has_placeholders(self, content):
        """Verify all of the required placeholders exist in a file"""
        for placeholder_pair in UpdateLicenseCode.placeholders:
            for placeholder in UpdateLicenseCode.placeholders[
                placeholder_pair
            ]:
                if content.find(placeholder) == -1:
                    return False
        return True

    def main(self):
        """Get the command line arguments, find the files, and process them"""
        if self.get_args() and self.get_path():
            file_list = [
                f
                for f in self.path.glob("*4.0*.html")
                if not os.path.islink(f)
            ]
            self.process_files(file_list)


if __name__ == "__main__":
    updater = UpdateLicenseCode()
    updater.main()
