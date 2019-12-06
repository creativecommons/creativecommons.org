# creativecommons.org

Website parent project, ([When we share, everyone wins - Creative
Commons][ccorg]), legalcode and translations, and primary GitHub repository for
public issues

[ccorg]: https://creativecommons.org/


## Overview

This repostory is currently for:
1. Public help and support [Issue] tracking
2. Legalcode and translations
3. Styles and other Includes
4. Instllation of the website

[issues]: https://github.com/creativecommons/creativecommons.org/issues


## Issues

This repository's [Issues][issues] is also the primary location for public help
and support issue tracking.


## Legalcode and Translations

The legalcode and translations files are located in
[`docroot/legalcode`](docroot/legalcode/).

Also see:
- [Legal Code Translation Policy - CC Public Wiki][wiki-legal-code]: Documents
  Legal/Translation process and policy
- [Legal Tools Translation - Creative Commons][fourstatus]: Translation status
  for 4.0 and CC0 licenses

[wiki-legal-code]: https://wiki.creativecommons.org/wiki/Legal_Code_Translation_Policy
[fourstatus]: https://wiki.creativecommons.org/wiki/Legal_Tools_Translation


### English Licenses
*Our public copyright licenses incorporate a unique and innovative
"three-layer" design*:
1. **Legal code**: the traditional legal tool *that most lawyers know and love*
2. Human Readable **Deed**: *a format that normal people can read... a handy
   reference for licensors and licensees*
3. Machine Readable **RDF**: *recognizes that software, from search engines to
   office productivity to music editing, plays an enormous role in the
   creation, copying, discovery, and distribution of works*
([Three “Layers” Of Licenses - About The Licenses - Creative
Commons][threelayer])

[threelayer]: https://creativecommons.org/licenses/#layers

License | Source File | Legal Code | Deed | RDF
------- | ----------- | ---------- | ---- | ---
CC BY-NC-ND 4.0 | [Source File][cc-by-nc-nd-source] | [Legal Code][cc-by-nc-nd-legalcode] | [Deed][cc-by-nc-nd-deed] | [RDF][cc-by-nc-nd-rdf]
CC BY-NC-SA 4.0 | [Source File][cc-by-nc-sa-source] | [Legal Code][cc-by-nc-sa-legalcode] | [Deed][cc-by-nc-sa-deed] | [RDF][cc-by-nc-sa-rdf]
CC BY-NC 4.0 | [Source File][cc-by-nc-source] | [Legal Code][cc-by-nc-legalcode] | [Deed][cc-by-nc-deed] | [RDF][cc-by-nc-rdf]
CC BY-ND 4.0 | [Source File][cc-by-nd-source] | [Legal Code][cc-by-nd-legalcode] | [Deed][cc-by-nd-deed] | [RDF][cc-by-nd-rdf]
CC BY-SA 4.0 | [Source File][cc-by-sa-source] | [Legal Code][cc-by-sa-legalcode] | [Deed][cc-by-sa-deed] | [RDF][cc-by-sa-rdf]
CC BY 4.0 | [Source File][cc-by-source] | [Legal Code][cc-by-legalcode] | [Deed][cc-by-deed] | [RDF][cc-by-rdf]
CC0 1.0 | [Source File][cc-zero-source] | [Legal Code][cc-zero-legalcode] | [Deed][cc-zero-deed] | [RDF][cc-zero-rdf]

[cc-by-nc-nd-source]: docroot/legalcode/by-nc-nd_4.0.html
[cc-by-nc-nd-legalcode]: https://creativecommons.org/licenses/by-nc-nd/4.0/legalcode.en
[cc-by-nc-nd-deed]: https://creativecommons.org/licenses/by-nc-nd/4.0/deed.en
[cc-by-nc-nd-rdf]: https://creativecommons.org/licenses/by-nc-nd/4.0/rdf

[cc-by-nc-sa-source]: docroot/legalcode/by-nc-sa_4.0.html
[cc-by-nc-sa-legalcode]: https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode.en
[cc-by-nc-sa-deed]: https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en
[cc-by-nc-sa-rdf]: https://creativecommons.org/licenses/by-nc-sa/4.0/rdf

[cc-by-nc-source]: docroot/legalcode/by-nc_4.0.html
[cc-by-nc-legalcode]: https://creativecommons.org/licenses/by-nc/4.0/legalcode.en
[cc-by-nc-deed]: https://creativecommons.org/licenses/by-nc/4.0/deed.en
[cc-by-nc-rdf]: https://creativecommons.org/licenses/by-nc/4.0/rdf

[cc-by-nd-source]: docroot/legalcode/by-nd_4.0.html
[cc-by-nd-legalcode]: https://creativecommons.org/licenses/by-nd/4.0/legalcode.en
[cc-by-nd-deed]: https://creativecommons.org/licenses/by-nd/4.0/deed.en
[cc-by-nd-rdf]: https://creativecommons.org/licenses/by-nd/4.0/rdf

[cc-by-sa-source]: docroot/legalcode/by-sa_4.0.html
[cc-by-sa-legalcode]: https://creativecommons.org/licenses/by-sa/4.0/legalcode.en
[cc-by-sa-deed]: https://creativecommons.org/licenses/by-sa/4.0/deed.en
[cc-by-sa-rdf]: https://creativecommons.org/licenses/by-sa/4.0/rdf

[cc-by-source]: docroot/legalcode/by_4.0.html
[cc-by-legalcode]: https://creativecommons.org/licenses/by/4.0/legalcode.en
[cc-by-deed]: https://creativecommons.org/licenses/by/4.0/deed.en
[cc-by-rdf]: https://creativecommons.org/licenses/by/4.0/rdf

[cc-zero-source]: docroot/legalcode/zero_1.0.html
[cc-zero-legalcode]: https://creativecommons.org/publicdomain/zero/1.0/legalcode.en
[cc-zero-deed]: https://creativecommons.org/publicdomain/zero/1.0/deed.en
[cc-zero-rdf]: https://creativecommons.org/publicdomain/zero/1.0/rdf


## Styles and other Includes

... **DOCUMENTATION IN PROGRESS** ...


## Installation

... **DOCUMENTATION IN PROGRESS** ...


### Sub-Repositories

In addition to this one, the following repositories are also used by the
website:

- License Engine

  - [creativecommons/cc.engine][ccengine]
  - [creativecommons/cc.i18n][cci18n])
  - [creativecommons/cc.license][cclicense]
  - [creativecommons/cc.licenserdf][cclicenserdf]
  - [creativecommons/rdfadict][rdfadict]

- WordPress

  - [creativecommons/new-creativecommons.org][neworg]

[ccengine]: https://github.com/creativecommons/cc.engine
[cci18n]: https://github.com/creativecommons/cc.i18n9
[cclicense]: https://github.com/creativecommons/cc.license
[cclicenserdf]: https://github.com/creativecommons/cc.licenserdf
[neworg]:https://github.com/creativecommons/new-creativecommons.org
[rdfadict]: https://github.com/creativecommons/rdfadict


## License

- [`LICENSE`](LICENSE) (Expat/[MIT][mit] License)

[mit]: http://www.opensource.org/licenses/MIT "The MIT License | Open Source Initiative"
