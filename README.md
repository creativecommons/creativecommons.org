# creativecommons.org

Website parent project ([When we share, everyone wins - Creative
Commons][ccorg]), legalcode and translations, and GitHub Issues for public
help and support

[ccorg]: https://creativecommons.org/


## Overview

This repository is currently for:
1. Public help and support [Issues][issues]
2. Legalcode and translations
3. Installation of the website (including Styles and other Includes)
4. ~~License Engine (ccEngine) Setup~~

[issues]: https://github.com/creativecommons/creativecommons.org/issues


## Code of Conduct

[`CODE_OF_CONDUCT.md`](CODE_OF_CONDUCT.md):
> The Creative Commons team is committed to fostering a welcoming community.
> This project and all other Creative Commons open source projects are governed
> by our [Code of Conduct][code_of_conduct]. Please report unacceptable
> behavior to [conduct@creativecommons.org](mailto:conduct@creativecommons.org)
> per our [reporting guidelines][reporting_guide].

[code_of_conduct]:https://opensource.creativecommons.org/community/code-of-conduct/
[reporting_guide]:https://opensource.creativecommons.org/community/code-of-conduct/enforcement/


## Contributing

See [`CONTRIBUTING.md`](CONTRIBUTING.md).


## Issues

This repository's [Issues][issues] is also the primary location for public help
and support.


## Legalcode and Translations

Relevant directories:
- [`docroot/legalcode`](docroot/legalcode/): legalcode and translations "source"
  files
- [`tools`](tools/): tools to assist with managing the translations

Also see:
- [Legal Code Translation Policy - CC Public Wiki][translatepolicy]: Documents
  Legal/Translation process and policy
- [Legal Tools Translation - CC Public Wiki][fourstatus]: Translation status
  for 4.0 and CC0 licenses

[translatepolicy]: https://wiki.creativecommons.org/wiki/Legal_Code_Translation_Policy
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


## Installation


### Child Repositories

In addition to this one, the following child repositories are also used:

- License Engine (chooser, deeds, legalcode, RDFs):

  - [creativecommons/cc.engine][ccengine]
  - [creativecommons/cc.i18n][cci18n]
  - [creativecommons/cc.license][cclicense]
  - [creativecommons/cc.licenserdf][cclicenserdf]
  - [creativecommons/rdfadict][rdfadict]

- WordPress and styles:

  - [creativecommons/new-creativecommons.org][neworg]

[ccengine]: https://github.com/creativecommons/cc.engine
[cci18n]: https://github.com/creativecommons/cc.i18n
[cclicense]: https://github.com/creativecommons/cc.license
[cclicenserdf]: https://github.com/creativecommons/cc.licenserdf
[rdfadict]: https://github.com/creativecommons/rdfadict

As of 2019 December, there are around 9,700 lines of python code split between
the repositories.


### License Engine Setup

> :warning: **We do not support local development at this time. Creative
> Commons maintains a staging server (configured per
> [creativecommons/sre-salt-prime][sre-salt-prime]) for development.**

1. **Install prerequisites**:
   - [Redland RDF Libraries][redland] Python bindings (`python-librdf` package
     on Debian. Due to this prerequisite, setup on macOS is *not* recommended.)
   - [pipenv][pipenvdocs] (`pipenv` package on Debian)
2. **Execute Install Script**: `./scripts/setup_engine.sh`
   ([`scripts/setup_engine.sh`](scripts/setup_engine.sh))
   1. Clones cc.engine and related respositories
      - Checks out specified branch (`ARG1`, defaults to `master`)
   2. Creates symlinks to support the semantic web
   3. Creates Python Environment via pipenv
   4. Generate ccengine.fcgi and copies config.ini into python_env
   5. Compiles mo files and transstats
      - Creates `transstats.csv` convenience symlink

[sre-salt-prime]: https://github.com/creativecommons/sre-salt-prime
[pipenvdocs]:https://pipenv.readthedocs.io/en/latest/
[redland]: http://librdf.org/


### Not Included

This project does not currently include the [creativecommons/cc.api][ccapi]
repository (which itself, depends on [creativecommons/cc.license][cclicense]).

[ccapi]: https://github.com/creativecommons/cc.api


### Styles and other Includes

:warning: **WARNING:** Any change to style or other includes must be duplicated
within the [creativecommons/new-creativecommons.org][neworg] repository.

[neworg]: https://github.com/creativecommons/new-creativecommons.org


## License

- [`LICENSE`](LICENSE) (Expat/[MIT][mit] License)

[mit]: http://www.opensource.org/licenses/MIT "The MIT License | Open Source Initiative"
