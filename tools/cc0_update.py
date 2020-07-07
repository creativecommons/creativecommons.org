#!/usr/bin/env python3
# vim: set fileencoding=utf-8:

"""Normalize file and add/update the language list at the bottom of all CC0
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
import argparse
import difflib
import glob
import os.path
import re
import sys
import traceback

# Local/library specific
import lang_tag_to


FAQ_TRANSLATION_LINK = "/faq/#officialtranslations"
FOOTER_COMMENTS = [
    "<!-- Language Footer Start - DO NOT DELETE -->",
    "<!-- Language Footer End - - DO NOT DELETE -->",
]


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


def update_lang_footer(args, filename, content, lang_tags):
    """Replace the contents of the language footer (everything between the
    FOOTER_COMMENTS) with a list of links based on the legalcode files
    currently present.
    """
    current_language = lang_tags_from_filenames(filename)[0]
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
            f'<a href="/publicdomain/zero/1.0/{index}">'
            f"{lang_tag_to.LABEL[lang_tag]}</a>,\n"
        )
        footer = f"{footer}{link}"
    footer = footer.rstrip(",\n")
    # Add the language footer block to the content
    start, end = FOOTER_COMMENTS
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
            f"{filename}:     Skipping unneeded insertion of language footer"
            " links"
        )
    else:
        print(f"{filename}: Inserting language footer links")
    if args.debug:
        new_content = content.replace(target, replacement, 1)
        diff_changes(filename, content, new_content)
        return new_content
    else:
        return content.replace(target, replacement, 1)


def has_footer_comments(content):
    """Determine if the FOOTER_COMMENTS are already present.
    """
    for comment in FOOTER_COMMENTS:
        if content.find(comment) == -1:
            return False
    return True


def insert_missing_lang_footer_comments(args, filename, content):
    """Insert the FOOTER_COMMENTS in the appropriate locations, if they are not
    present.
    """
    if has_footer_comments(content):
        print(
            f"{filename}:     Skipping unneeded language footer comments"
            "insertion"
        )
        return content
    print(f"{filename}: inserting language footer HTML comments")
    re_pattern = re.compile(
        f"""
        (?P<prefix>
            <blockquote>\\s*<a\\ id="languages">[^<]*</a>[^<]+
        )
        (?P<target>
            # Maches all language link anchors
            #      Period or Ideographic Full Stop (\u3002)
            .+</a>[.\u3002]
        )
        (?P<suffix>
            .*"{FAQ_TRANSLATION_LINK}"
        )
        """,
        re.DOTALL | re.MULTILINE | re.VERBOSE,
    )
    matches = re_pattern.search(content)
    if matches is None:
        print(
            f"{filename}: ERROR: language block not matched. Aborting"
            " processing"
        )
        return None
    target = matches.group("target")
    replacement = (
        f"\n{FOOTER_COMMENTS[0]}\n"
        f"{target.strip()}\n"
        f"{FOOTER_COMMENTS[1]}\n"
    )
    if args.debug:
        new_content = content.replace(target, replacement, 1)
        diff_changes(filename, content, new_content)
        return new_content
    else:
        return content.replace(target, replacement, 1)


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
        return
    target = matches.group("target")
    replacement = FAQ_TRANSLATION_LINK
    if args.debug:
        new_content = content.replace(target, replacement, 1)
        diff_changes(filename, content, new_content)
        return new_content
    else:
        return content.replace(target, replacement, 1)


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
        return
    target = matches.group()
    replacement = 'id="languages"'
    if args.debug:
        new_content = content.replace(target, replacement, 1)
        diff_changes(filename, content, new_content)
        return new_content
    else:
        return content.replace(target, replacement, 1)


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
    """Process each of the CC0 legalcode files and update them, as necessary.
    """
    for filename in file_list:
        with open(filename, "r", encoding="utf-8", newline="") as file_in:
            content = file_in.read()
        new_content = content
        new_content = normalize_line_endings(args, filename, new_content)
        new_content = normalize_languages_anchor(args, filename, new_content)
        if new_content is None:
            sys.exit(1)
        new_content = normalize_faq_translation_link(
            args, filename, new_content
        )
        if new_content is None:
            sys.exit(1)
        new_content = insert_missing_lang_footer_comments(
            args, filename, new_content
        )
        if new_content is None:
            sys.exit(1)
        new_content = update_lang_footer(
            args, filename, new_content, lang_tags
        )
        if new_content is None:
            sys.exit(1)
        if content == new_content:
            print(
                f"{filename}:     Skipping writing back to file (no changes)"
            )
        elif args.debug:
            print(f"{filename}: DEBUG:     Skipping writing changes to file")
        else:
            print(f"{filename}: Writing changes to file")
            with open(filename, "w", encoding="utf-8") as file_out:
                file_out.write(new_content)
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
    default_glob = ["zero_1.0*.html"]
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
