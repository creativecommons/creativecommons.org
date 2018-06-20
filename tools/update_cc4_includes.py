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
        'footer': ('<!-- Site Footer Start - DO NOT DELETE -->', '<!-- Site Footer End - DO NOT DELETE -->'), \
        'language-selector': ('<!-- Language Selector Start - DO NOT DELETE -->', '<!-- Language Selector End - DO NOT DELETE -->') \
    }

    languages = {}

    iso_to_language = { \
        'ar': 'العربية',  'de': 'Deutsch',      'en': 'English',          'fi': 'suomeksi',  \
        'fa': 'پارسی', \
        'fr': 'français', 'hr': 'hrvatski',     'id': 'bahasa Indonesia', 'it': 'italiano',  \
        'ja': '日本語',    'mi': 'Te Reo Māori', 'nl': 'Nederlands',       'no': 'norsk',     \
        'pl': 'polski',   'sv': 'svenska',      'tr': 'Türkçe',           'uk': 'українська', \
        'ru': 'русский',  'pt': 'Português',    'lt': 'Lietuvių',         'lv': 'Latviski', \
        'es': 'Español',  'ms': 'Bahasa Malaysia', 'ca': 'Català',        'da': 'Dansk', \
        'eo': 'Esperanto',   'gl': 'Galego',       'hu': 'Magyar',        'ro': 'română', \
        'sl': 'Slovenščina', 'is': 'Íslenska',     'cs': 'čeština',       'el': 'Ελληνικά',  \
        'be': 'Беларуская',  'bn': 'বাংলা',         'zh': '中文',           'ko': '한국어', \
        'es_ES': 'Castellano (España)' \
    }

    lang_sel_text = { \
        'ar': 'هذة الصفحة متوفرة باللغات التالية:', \
        'be': 'Гэта старонка даступная на наступных мовах:', \
        'bn': 'পৃষ্ঠাটি নিন্মোক্ত ভাষায় বিদ্যমান রয়েছে:', \
        'ca': 'Aquesta pàgina està disponible en els idiomes següents:', \
        'cs': 'Tato stránka je k dispozici v následujících jazycích:', \
        'da': 'Denne side er tilgængelig på følgende sprog:', \
        'de': 'Diese Seite ist in folgenden Sprachen verfügbar:', \
        'el': 'Η σελίδα αυτή είναι διαθέσιμη στις ακόλουθες γλώσσες:', \
        'en': 'This page is available in the following languages:', \
        'eo': 'Ĉi tiu paĝo estas disponebla en la jenaj lingvoj:', \
        'es': 'Esta página está disponible en los siguientes idiomas:', \
        'es_ES': '', \
        'fa': 'این صفحه به زبان های زیر در دسترس است : ', \
        'fi': 'Tämä sivu on saatavilla seuraavilla kielillä:', \
        'fr': 'Cette page existe aussi dans les langues suivantes :', \
        'gl': 'Esta páxina tamén está dispoñíbel nos idiomas seguintes:', \
        'hr': 'Ova stranica je dostupna na sljedećim jezicima:', \
        'hu': 'Ez az oldal az alábbi nyelveken érhető még el:', \
        'id': 'Laman ini tersedia dalam bahasa berikut:', \
        'is': 'Þessi síða er tiltæk á eftirfarandi tungumálum:', \
        'it': 'Questa pagina è disponibile nelle seguenti lingue:', \
        'ja': 'このページは以下の言語でもご覧になれます:', \
        'ko': '이 페이지는 다음의 언어로 이용할 수 있습니다.', \
        'lt': 'Šis puslapis yra prienamas šiomis kalbomis:', \
        'lv': 'Šī lapa ir pieejama sekojošās valodās:', \
        'mi': 'E wātea ana tēnei whārangi i ēnei reo:', \
        'ms': 'Halaman ini boleh didapati dalam bahasa-bahasa berikut:', \
        'nl': 'Deze pagina is beschikbaar in de volgende talen:', \
        'no': 'Denne siden er tilgjengelig på følgende språk:', \
        'pl': 'Strona jest dostępna w następujących językach:', \
        'pt': 'Esta página está disponível nas seguintes línguas:', \
        'ro': 'Această pagină este disponibilă în următoarele limbi:', \
        'ru': 'Эта страница доступна на следующих языках:', \
        'sl': 'Ta stran je dosegljiva v naslednjih jezikih:', \
        'sv': 'Denna sida finns tillgänglig på följande språk:', \
        'tr': 'Bu sayfa şu dillerde mevcuttur:', \
        'uk': 'Ця сторінка доступна наступними мовами:', \
        'zh': '声明：' \
    }

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
            content = self.add_language_selector(content, filepath)
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
        
    def add_language_selector(self, content, filepath):
        """Build and insert a language selector dropdown list."""
        # Get a list of all the other languages for this license type and store it so
        # it can be reused.
        license_data = self.parse_filename(filepath)
        if license_data['type'] not in self.languages:
            self.languages[license_data['type']] = []
            glob_string = license_data['type'] + '_' + license_data['version'] + '*.html'
            language_file_list = [f for f in self.path.glob(glob_string)]
            for filepath in language_file_list:
                sibling_data = self.parse_filename(filepath)
                self.languages[license_data['type']].append(sibling_data['language'])
            self.languages[license_data['type']].sort()

        current_language = license_data['language']
        sibling_languages = self.languages[license_data['type']]

        selector =  '<div id="language-selector-block" class="container">'
        selector += '  <div class="language-selector-inner">'
        selector += self.lang_sel_text[current_language]
        selector += '    <img class="language-icon" src="/images/language_icon_x2.png" alt="Languages" />'
        selector += '    <select>'
        for iso_code in sibling_languages:
            # Set the selected option to the current language of the page
            selected = ''
            if iso_code == current_language:
                selected = ' selected="selected" '
            # Determine to option value for the language. English breaks the pattern so handle it differently.
            option_value = 'legalcode.' + iso_code
            if iso_code == 'en':
                option_value = 'legalcode'
            # Add the selector vlaue
            selector += '<option value="' + option_value + '"' + selected + '>' + self.iso_to_language[iso_code] + '</option>'
        selector += '    </select>'
        selector += '  </div>'
        selector += '</div>'

        # Add the language selector block to the content
        start, end = UpdateLicenseCode.placeholders['language-selector']
        target_string = re.search(start + '.*?' + end, content, re.DOTALL).group()
        replacement = start + "\n" + selector + "\n" + end
        content = content.replace(target_string, replacement, 1)

        return content

    def parse_filename(self, filepath):
        license_info = filepath.name[0:-5].split('_');
        type = license_info[0]
        version = license_info[1]
        if len(license_info) > 2:
            language = license_info[2]
        else:
            language = 'en'
        return {'type': type, 'version': version, 'language': language}

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
