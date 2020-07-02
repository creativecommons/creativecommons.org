#!/usr/bin/env python3
# vim: set fileencoding=utf-8:

"""Normalize file and add/update the language list at the bottom of all CC4
legalcode files.
"""

# Copyright 2016, 2017 Creative Commons
#
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
from collections import OrderedDict
import argparse
import difflib
import glob
import os.path
import re
import sys
import traceback

# Local/library specific
import lang_tag_to


COMMENTS = OrderedDict(
    {
        "head_start": {
            "label": "Head Start",
            "regex": re.compile(
                r"""(?P<target>
                /errata[.]js['"]></script>\s*
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
            "include": "html-head.html",
        },
        "head_end": {
            "label": "Head End",
            "regex": re.compile(
                r"""(?P<target>
                \s*</head>
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "site_header_start": {
            "label": "Site Header Start",
            "regex": re.compile(
                r"""(?P<target>
                <body[^>]+>\s
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
            "include": "site-header.html",
        },
        "site_header_end": {
            "label": "Site Header End",
            "regex": re.compile(
                r"""(?P<target>
                <!--\ Language\ Selector\ Start
                | \s*<div\ id="language-selector-block"
                | \s*<div\ id="deed"
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "language_selector_start": {
            "label": "Language Selector Start",
            "regex": re.compile(
                r"""(?P<target>
                <!--\ Site\ Header\ End\ -\ DO\ NOT\ DELETE\ -->\s
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "language_selector_end": {
            "label": "Language Selector End",
            "regex": re.compile(
                r"""(?P<target>
                <!--\ Legalcode\ Start
                | \s*<div\ id="deed"
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "legalcode_start": {
            "label": "Legalcode Start",
            "regex": re.compile(
                r"""(?P<target>
                <!--\ Language\ Selector\ End\ -\ DO\ NOT\ DELETE\ -->\s
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "legalcode_end": {
            "label": "Legalcode End",
            "regex": re.compile(
                r"""(?P<target>
                \s*<p\ class="shaded">.*<br><br>
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "language_footer_start": {
            "label": "Language Footer Start",
            "regex": re.compile(
                r"""(?P<target>
                <a\ id="languages"></a>[^<]+\s
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "language_footer_end": {
            "label": "Language Footer End",
            "regex": re.compile(
                r"""(?P<target>
                (?<=
                    # \u3002 is ideographic full stop
                    </a>[.\u3002]
                )
                \s*[^<]+<a\ href="/FAQ#officialtranslations">
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
        "site_footer_start": {
            "label": "Site Footer Start",
            "regex": re.compile(
                r"""(?P<target>
                <div\ id="deed-foot">\s*
                <p[^<]+<a[^<]+</a></p>\s*</div>\s*
                </div>\s*
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
            "include": "site-footer.html",
        },
        "site_footer_end": {
            "label": "Site Footer End",
            "regex": re.compile(
                r"""(?P<target>
                \s*</body>
            )""",
                re.IGNORECASE | re.VERBOSE,
            ),
        },
    }
)
FAQ_TRANSLATION_LINK = "/faq/#officialtranslations"


class ToolError(Exception):
    def __init__(self, message, code=None):
        self.code = code if code else 1
        message = "({}) {}".format(self.code, message)
        super(ToolError, self).__init__(message)


def diff_changes(filename, old, new):
    """Display changes as a colorized unified diff.
    """
    diff = list(
        difflib.unified_diff(
            old.split("\n"),
            new.split("\n"),
            fromfile=f"{filename}: current",
            tofile=f"{filename}: proposed",
            n=3,
        )
    )
    if not diff:
        return
    # Color diff output
    rst = "\033[0m"
    for i, line in enumerate(diff):
        if line.startswith("---"):
            diff[i] = f"\033[91m{line.rstrip()}{rst}"
        elif line.startswith("+++"):
            diff[i] = f"\033[92m{line.rstrip()}{rst}"
        elif line.startswith("@"):
            diff[i] = f"\033[36m{line.rstrip()}{rst}"
        elif line.startswith("-"):
            diff[i] = f"\033[31m{line}{rst}"
        elif line.startswith("+"):
            diff[i] = f"\033[32m{line}{rst}"
        else:
            diff[i] = f"\033[90m{line}{rst}"
    print("\n".join(diff))


def update_include(args, filename, content, section):
    label = COMMENTS[f"{section}_start"]["label"]
    start = f"<!-- {label} - DO NOT DELETE -->"
    label = COMMENTS[f"{section}_end"]["label"]
    end = f"<!-- {label} - DO NOT DELETE -->"
    include_file = COMMENTS[f"{section}_start"]["include"]
    include = os.path.join(sys.path[0], "legalcode-includes", include_file)
    with open(include, "r", encoding="utf-8") as file_in:
        includetext = file_in.read()
    target = re.search(f"{start}.*{end}", content, re.DOTALL).group()
    replacement = f"{start}\n{includetext}\n{end}"
    if target == replacement:
        print(f"{filename}:     Skipping unneeded update of {section} include")
    else:
        print(f"{filename}: Updating {section} include")
    new_content = content.replace(target, replacement, 1)
    if args.debug:
        diff_changes(filename, content, new_content)
    if new_content is None:
        sys.exit(1)
    return new_content


def update_lang_selector(args, filename, content, lang_tags):
    """Replace the contents of the language selector (everything between
    language_selector_start and language_selector_end HTML comments) with a
    list of links based on the legalcode files currently being processed.
    """
    current_language = lang_tags_from_filenames(filename)[0]
    selector = (
        '<div id="language-selector-block" class="container">'
        '\n<div class="language-selector-inner">'
        f"\n{lang_tag_to.SELECT_TEXT[current_language]}"
        '\n<img class="language-icon"'
        ' src="/images/language_icon_x2.png" alt="Languages">'
        "\n<select>"
    )
    for lang_tag in lang_tags:
        selected = ""
        if lang_tag == current_language:
            selected = ' selected="selected"'
        # Determine to option value for the language. English breaks the
        # pattern so handle it differently.
        if lang_tag == "en":
            index = "legalcode"
        else:
            index = f"legalcode.{lang_tag}"

        # Add the selector vlaue
        selector = (
            f"{selector}\n"
            f'<option value="{index}"{selected}>'
            f"{lang_tag_to.LABEL[lang_tag]}</option>"
        )
    selector = f"{selector}\n</select>\n</div>\n</div>"
    # Update the language selector block to the content
    label = COMMENTS["language_selector_start"]["label"]
    start = f"<!-- {label} - DO NOT DELETE -->"
    label = COMMENTS["language_selector_end"]["label"]
    end = f"<!-- {label} - DO NOT DELETE -->"
    target = re.search(f"{start}.*{end}", content, re.DOTALL).group()
    replacement = f"{start}\n{selector}\n{end}"
    if target == replacement:
        print(
            f"{filename}:     Skipping unneeded update of language"
            " selector options"
        )
    else:
        print(f"{filename}: Updating language selector options")
    new_content = content.replace(target, replacement, 1)
    if args.debug:
        diff_changes(filename, content, new_content)
    if new_content is None:
        sys.exit(1)
    return new_content


def update_lang_footer(args, filename, content, lang_tags):
    """Replace the contents of the language footer (everything between
    language_footer_start and language_footer_end HTML comments) with a
    list of links based on the legalcode files currently being processed.
    """
    current_language = lang_tags_from_filenames(filename)[0]
    license_type = filename.split("_")[0]
    footer = ""
    for lang_tag in lang_tags:
        if lang_tag == current_language:
            continue
        # Determine to option value for the language. English breaks the
        # pattern so handle it differently.
        if lang_tag == "en":
            index = "legalcode"
        else:
            index = f"legalcode.{lang_tag}"
        link = (
            f'<a href="/licenses/{license_type}/4.0/{index}">'
            f"{lang_tag_to.LABEL[lang_tag]}</a>,\n"
        )
        footer = f"{footer}{link}"
    footer = footer.rstrip(",\n")
    # Update the language footer block to the content
    label = COMMENTS["language_footer_start"]["label"]
    start = f"<!-- {label} - DO NOT DELETE -->"
    label = COMMENTS["language_footer_end"]["label"]
    end = f"<!-- {label} - DO NOT DELETE -->"
    target = re.search(f"{start}.*{end}", content, re.DOTALL).group()
    if current_language in ["ja", "zh-Hans", "zh-Hant"]:
        # Use ideographic full stop ("。")
        period = "\u3002"
    else:
        # Use ASCII period
        period = "."
    replacement = f"{start}\n{footer}{period}\n{end}"
    if target == replacement:
        print(
            f"{filename}:     Skipping unneeded update of language footer"
            " links"
        )
    else:
        print(f"{filename}: Updating language footer links")
    new_content = content.replace(target, replacement, 1)
    if args.debug:
        diff_changes(filename, content, new_content)
    if new_content is None:
        sys.exit(1)
    return new_content


def insert_missing_comment(args, filename, content, comment_dict):
    """Insert the comment in the appropriate locations, if it is not already
    present.
    """
    label = comment_dict["label"]
    comment = f"<!-- {label} - DO NOT DELETE -->"
    regex = comment_dict["regex"]
    if not content.find(comment) == -1:
        print(
            f"{filename}:     Skipping unneeded {label} HTML comment insertion"
        )
        return content
    print(f"{filename}: inserting {label } HTML comment")
    matches = regex.search(content)
    if matches is None:
        print(
            f"{filename}: ERROR: {label} insertion point not matched. Aborting"
            " processing"
        )
        sys.exit(1)
    target = matches.group("target")
    if " start" in label.lower():
        # Start comments are inserted after target regex
        target_new = target.rstrip()
        replacement = f"{target_new}\n{comment}\n"
    else:
        # End comments are inserted before target regex
        target_new = target.lstrip("\n")
        replacement = f"\n{comment}\n{target_new}"
    new_content = content.replace(target, replacement, 1)
    if args.debug:
        diff_changes(filename, content, new_content)
    if new_content is None:
        sys.exit(1)
    return new_content


def has_correct_faq_officialtranslations(content):
    """Determine if the link to the translation FAQ is correct.
    """
    if content.find(f'"{FAQ_TRANSLATION_LINK}"') == -1:
        return False
    return True


def normalize_faq_translation_link(args, filename, content):
    """Replace various incorrect translation FAQ links with the correct link
    (FAQ_TRANSLATION_LINK).
    """
    if has_correct_faq_officialtranslations(content):
        print(
            f"{filename}:     Skipping unneeded translation FAQ link"
            " normalization"
        )
        return content
    print(f"{filename}: normalizing translation FAQ link")
    re_pattern = re.compile(
        r"""
        (?P<prefix>
            href=['"]
        )
        (?P<target>
            # Matches various translation FAQ URLs
            [^'"]*/[Ff][Aa][Qq]/?[#][^'"]*
        )
        (?P<suffix>
            ['"]
        )
        """,
        re.DOTALL | re.MULTILINE | re.VERBOSE,
    )
    matches = re_pattern.search(content)
    if matches is None:
        print(
            f"{filename}: ERROR: translation link not matched. Aborting"
            " processing"
        )
        sys.exit(1)
    target = matches.group("target")
    replacement = FAQ_TRANSLATION_LINK
    new_content = content.replace(target, replacement, 1)
    if args.debug:
        diff_changes(filename, content, new_content)
    if new_content is None:
        sys.exit(1)
    return new_content


def has_correct_languages_anchor(content):
    """Determine if language anchor uses id
    """
    if content.find('id="languages"') == -1:
        return False
    return True


def normalize_languages_anchor(args, filename, content):
    """Replace name with id in languages anchor (HTML5 compatibility)
    """
    if has_correct_languages_anchor(content):
        print(
            f"{filename}:     Skipping unneeded language anchor normalization"
        )
        return content
    print(f"{filename}: normalizing language anchor id")
    re_pattern = re.compile("name=['\"]languages['\"]", re.IGNORECASE)
    matches = re_pattern.search(content)
    if matches is None:
        print(
            f"{filename}: ERROR: languages anchor not matched. Aborting"
            " processing"
        )
        sys.exit(1)
    target = matches.group()
    replacement = 'id="languages"'
    new_content = content.replace(target, replacement, 1)
    if args.debug:
        diff_changes(filename, content, new_content)
    if new_content is None:
        sys.exit(1)
    return new_content


def normalize_line_endings(args, filename, content):
    """Normalize line endings to unix LF (\\n)
    """
    re_pattern = re.compile("\r(?!\n)")
    matches = re_pattern.findall(content)
    message = ""
    if matches:
        message = f" {len(matches)} mac newlines (CR)"
    re_pattern = re.compile("\r\n")
    matches = re_pattern.findall(content)
    if matches:
        if message:
            message = f"{message} and"
        message = f"{message} {len(matches)} windows newlines (CRLF)"
    if message:
        print(f"{filename}: Converting{message} to unix newlines (LF)")
        return "\n".join(content.split("\r\n"))
    else:
        print(f"{filename}:     Skipping unneeded newline conversion")
        return content


def process_file_contents(args, file_list, lang_tags):
    """Process each of the CC4 legalcode files and update them, as necessary.
    """
    for filename in file_list:
        with open(filename, "r", encoding="utf-8", newline="") as file_in:
            content = file_in.read()
        new_content = content
        new_content = normalize_line_endings(args, filename, new_content)
        new_content = normalize_languages_anchor(args, filename, new_content)
        new_content = normalize_faq_translation_link(
            args, filename, new_content
        )
        for key in COMMENTS.keys():
            new_content = insert_missing_comment(
                args, filename, new_content, COMMENTS[key]
            )
        new_content = update_lang_selector(
            args, filename, new_content, lang_tags
        )
        new_content = update_lang_footer(
            args, filename, new_content, lang_tags
        )
        for section in ("head", "site_header", "site_footer"):
            new_content = update_include(args, filename, new_content, section)
        if content == new_content:
            print(
                f"{filename}:     Skipping writing back to file (no changes)"
            )
        elif args.debug:
            print(f"{filename}:     DEBUG: Skipping writing changes to file")
        else:
            print(f"{filename}: Writing changes to file")
            with open(filename, "w", encoding="utf-8") as file_out:
                file_out.write(new_content)
        print()
        print()


def lang_tags_from_filenames(file_list):
    """Extract RFC 5646 language tags from filename(s)
    """
    if isinstance(file_list, str):
        lang_tags = [file_list.split(".")[1][2:]]
    else:
        lang_tags = list(
            set([filename.split(".")[1][2:] for filename in file_list])
        )
    try:
        lang_tags[lang_tags.index("")] = "en"
    except ValueError:
        pass
    lang_tags.sort()
    return lang_tags


def setup():
    """Instantiate and configure argparse and logging.

    Return argsparse namespace.
    """
    default_glob = ["by*4.0*.html"]
    ap = argparse.ArgumentParser(description=__doc__)
    ap.add_argument(
        "-d",
        "--debug",
        action="store_true",
        help="Debug mode: list changes without modification",
    )
    ap.add_argument(
        "globs",
        nargs="*",
        default=default_glob,
        help=(
            "Filename or shell glob of the file(s) that will be updated"
            f' (default: "{default_glob[0]}")'
        ),
        metavar="FILENAME",
    )
    args = ap.parse_args()
    return args


def main():
    args = setup()
    file_list = sorted(
        list(
            set(
                [
                    filename
                    for fileglob in args.globs
                    for filename in glob.glob(fileglob)
                    if os.path.isfile(filename)
                    if not os.path.islink(filename)
                ]
            )
        )
    )
    lang_tags = lang_tags_from_filenames(file_list)
    process_file_contents(args, file_list, lang_tags)


if __name__ == "__main__":
    try:
        main()
    except SystemExit as e:
        sys.exit(e.code)
    except KeyboardInterrupt:
        print("INFO (130) Halted via KeyboardInterrupt.", file=sys.stderr)
        sys.exit(130)
    except ToolError:
        error_type, error_value, error_traceback = sys.exc_info()
        print("CRITICAL {}".format(error_value), file=sys.stderr)
        sys.exit(error_value.code)
    except:  # noqa: ignore flake8: E722 do not use bare 'except'
        print("ERROR (1) Unhandled exception:", file=sys.stderr)
        print(traceback.print_exc(), file=sys.stderr)
        sys.exit(1)
