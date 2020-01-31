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

from pathlib import Path
import getopt
import os
import re
import sys


class UpdateLicenseCode(object):
    """One time script modifying 4.0 legal code files for updated look. This
    does not change legal code language. It adds a header and footer
    placeholders, and updates the HTML head.

    This allows the update_cc4_code.py script to function."""

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
        "legalcode": (
            "<!-- Legalcode Start - DO NOT DELETE -->",
            "<!-- Legalcode End - DO NOT DELETE -->",
        ),
        "language-footer": (
            "<!-- Language Footer Start - DO NOT DELETE -->",
            "<!-- Language Footer End - DO NOT DELETE -->",
        ),
    }

    image_map = {
        "by": {
            "file": "attribution_icon_white.svg",
            "alt_text": "Attribution",
        },
        "sa": {"file": "sa_white.svg", "alt_text": "Share Alike"},
        "nd": {"file": "nd_white.svg", "alt_text": "No Derivatives"},
        "nc": {"file": "nc_white.svg", "alt_text": "Non-Commerical"},
    }

    def usage(self):
        print("")
        print("prep_cc4_code.py [-v]")
        print("  -v: Verbose output")
        print("")
        print("  e.g. prep_cc4_code.py")
        print("       prep_cc4_code.py -v")

    def log(self, message, type="standard"):
        if (type == "standard") or (type == "verbose" and self.verbose):
            print(message)

    def get_args(self):
        """Get arguments/options and set corresponding flags. On validation
        error print usage help"""
        try:
            opts, args = getopt.getopt(sys.argv[1:], "va")
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
            self.includes_path = self.path / "includes"
        return self.path is not False

    def process_files(self, filelist):
        """File processing loop"""
        for filepath in filelist:
            self.process_file(filepath)

    def process_file(self, filepath):
        """Make the required changes to prepare the file:
           - Add HEAD, site header and site footer placeholders
           - Remove references to deed3 css files
           - Remove inline styles
           - Remove Creative Commons text header"""
        self.log(f"Processing: {filepath.name}", "verbose")
        with filepath.open(encoding="utf-8") as infile:
            content = infile.read()
        license_attrs = self.get_license_attrs(filepath.name)

        content = self.handle_placeholders(content)
        content = self.remove_deed3_css(content)
        content = self.handle_rtl_css(content)
        content = self.remove_old_text_header(content)
        content = self.remove_inline_styles(content)
        content = self.remove_unported_image(content)
        content = self.add_language_class(content, license_attrs["language"])
        content = self.add_type_logos(content, license_attrs["type"])
        content = self.handling_consideration_blockquotes(content)

        self.log(f"   Updating content: {filepath.name}", "verbose")
        with filepath.open("w", encoding="utf-8") as outfile:
            outfile.write(content)

    def handle_placeholders(self, content):
        self.log("   Adding placeholders", "verbose")
        # The language selector has to come after the header. Because
        # dictionaries don't maintain order the easiest way to maintain order
        # is sorting the interation keys.
        for placeholder_pair in sorted(UpdateLicenseCode.placeholders):
            if self.has_placeholders(content, placeholder_pair):
                self.log(
                    f"   Found placeholder: {placeholder_pair}, skipping",
                    "verbose",
                )
            else:
                start, end = UpdateLicenseCode.placeholders[placeholder_pair]
                if placeholder_pair == "head":
                    target = "</head>"
                    replacement = f"{start}\n{end}\n{target}"
                elif placeholder_pair == "header":
                    target = re.search("<body.*?>", content).group()
                    replacement = f"{target}\n{start}\n{end}"
                elif placeholder_pair == "footer":
                    target = "</body>"
                    replacement = f"{start}\n{end}\n{target}"
                elif placeholder_pair == "language-selector":
                    target = "<!-- Site Header End - DO NOT DELETE -->"
                    replacement = f"{target}\n{start}\n{end}"
                elif placeholder_pair == "legalcode":
                    re_pattern = re.compile(
                        r"""
                        # Legalcode
                        ^\s*<div\ id="deed"
                        .*
                        ^\s*<li\ id="s8d">.*</li>\s*</ol>$
                        (?=\s*<p\ class="shaded">)
                        """,
                        re.DOTALL | re.MULTILINE | re.VERBOSE,
                    )
                    target = re_pattern.search(content).group()
                    replacement = f"\n{start}\n{target.strip()}\n{end}\n"
                elif placeholder_pair == "language-footer":
                    re_pattern = re.compile(
                        r"""
                        # Language footer
                        (?P<prefix>
                            ^\s*<p\ class="shaded(?:\ a-nobreak)?">\s*
                            <a(?:\ name="languages")?\ id="languages">
                            .*?</a>[^<]+
                        )
                        (?P<languages>
                            # \u3002 is ideographic full stop
                            .*?</a>[.\u3002]
                        )
                        (?=.*officialtranslations)
                        """,
                        re.DOTALL | re.MULTILINE | re.VERBOSE,
                    )
                    target = re_pattern.search(content).group("languages")
                    replacement = f"\n{start}\n{target.strip()}\n{end}\n"
                content = content.replace(target, replacement, 1)
        return content

    def has_placeholders(self, content, pair_name):
        """Verify the specified placeholder pair exist in content string"""
        placeholders = UpdateLicenseCode.placeholders[pair_name]
        for placeholder in placeholders:
            if content.find(placeholder) == -1:
                return False
        return True

    def remove_deed3_css(self, content):
        """Remove refererences to deed3 css stylesheets from HEAD"""
        self.log("   Removing deed3 css references from head", "verbose")
        content = re.sub(r"\n.*?<link.*?deed3\.css.*?>.*?\n", "\n", content)
        content = re.sub(
            r"\n.*?<link.*?deed3\-print\.css.*?>.*?\n", "\n", content
        )
        content = re.sub(
            r"\n.*?<link.*?deed3\-ie\.css.*?>.*?\n", "\n", content
        )
        return content

    def handle_rtl_css(self, content):
        """The Right-to-Left stylesheet needs to come after the HEAD includes
            and be renamed"""
        self.log("   Handling right to left css", "verbose")
        if content.find("deed3-rtl.css") != -1:
            content = re.sub(
                r"\n.*?<link.*?deed3\-rtl\.css.*?>.*?\n", "\n", content
            )
            bottom_placholder = UpdateLicenseCode.placeholders["head"][1]
            new_rtl_css = (
                '<link rel="stylesheet" type="text/css"'
                ' href="/includes/legalcode-rtl.css" media="all">'
            )
            content = content.replace(
                bottom_placholder, bottom_placholder + "\n" + new_rtl_css
            )
        return content

    def remove_old_text_header(self, content):
        """Remove the paragraph string with id=header"""
        self.log('   Removing paragraph with id="header"', "verbose")
        content = re.sub(r'<p.*?id="header".*?</p>', "", content, 0, re.DOTALL)
        return content

    def remove_inline_styles(self, content):
        """Remove inline styles"""
        self.log("   Remove inline styles", "verbose")
        content = re.sub(r"<style.*?</style>", "", content, 0, re.DOTALL)
        return content

    def remove_unported_image(self, content):
        """Remove inline styles"""
        self.log("   Remove unported image", "verbose")
        content = re.sub(r"<img.*?src=.*?unported\.png.*?>", "", content)
        return content

    def add_language_class(self, content, language_code):
        """Add language class to body tag"""
        self.log("   Add language class to body tag", "verbose")
        if not language_code:
            language_code = "en"
        language_class = "lang-" + language_code
        body_tag = re.search("<body.*?>", content, re.IGNORECASE).group()
        if body_tag.find(language_class) == -1:
            # If language class not on body, add it
            if body_tag.find("class") > 0:
                existing_classes = re.search('class="(.*?)"', body_tag).group(
                    1
                )
                new_body_tag = (
                    '<body class="'
                    + existing_classes
                    + " "
                    + language_class
                    + '">'
                )
            else:
                new_body_tag = '<body class="' + language_class + '">'
            content = content.replace(body_tag, new_body_tag)
        return content

    def get_license_attrs(self, filename):
        parts = filename.replace(".html", "").split("_")
        lic_type = parts[0]
        version = parts[1]
        language = ""
        if len(parts) == 3:
            language = parts[2]
        return {"language": language, "version": version, "type": lic_type}

    def add_type_logos(self, content, lic_type):
        lic_type_attrs = lic_type.split("-")
        lic_images = ""
        for lic_attr in lic_type_attrs:
            filename = UpdateLicenseCode.image_map[lic_attr]["file"]
            alt_text = UpdateLicenseCode.image_map[lic_attr]["alt_text"]
            image_tag = (
                f'<img src="/images/deed/svg/{filename}" alt="{alt_text}"/>'
            )
            lic_images += (
                f'<span class="cc-icon-{lic_attr}">{image_tag}</span>'
            )
        cc_logo_section = re.search(
            '<div id="cc-logo">.*?</div>', content, re.DOTALL
        ).group()
        new_cc_logo_section = (
            '<div id="cc-logo"><span class="cc-icon-logo">'
            '<img src="/images/deed/svg/cc_white.svg" alt="CC"/></span>'
            f"{lic_images}</div>"
        )
        content = content.replace(cc_logo_section, new_cc_logo_section)
        return content

    def handling_consideration_blockquotes(self, content):
        content = content.replace(
            "<blockquote>", '<p class="usage-considerations">'
        )
        content = content.replace("</blockquote>", "</p>")
        return content

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
