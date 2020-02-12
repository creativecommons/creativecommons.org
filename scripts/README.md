## translations-merge-update.sh


### Transifex configurations


#### `~/.transifexrc`

On server:
- `licenses` host
  - `/var/www/.transifexrc`:
    ```ini
    [https://www.transifex.com]
    api_hostname = https://api.transifex.com
    hostname = https://www.transifex.com
    password = [REDACTED]
    username = api
    ```


#### `.tx/config`

In repo:
- [creativecommons/cc.i18n][cc-i18n]
  - [`.tx/config`][tx-config]

On server:
- `licenses` host
  - `/var/www/creativecommons.org/python_env/src/cc.i18n/.tx/config`:
    ```ini
    [main]
    host = https://www.transifex.com

    [CC.deeds-choosers]
    file_filter = cc/i18n/po/<lang>/cc_org.po
    lang_map = sr@latin:sr_LATN, zh_CN:zh
    source_file = cc/i18n/po/en/cc_org.po
    source_lang = en
    ```
    - Specifies the **project** (`CC`) and the **resource** (`deeds-choosers`)


[cc-i18n]: https://github.com/creativecommons/cc.i18n
[tx-config]: https://github.com/creativecommons/cc.i18n/blob/master/.tx/config


#### Documentation

- [Configuration Files | Transifex
  Documentation](https://docs.transifex.com/client/client-configuration)


### sudo configuration

On the `licenses` host, this script requires
`/etc/sudoers.d/translations-merge-update` with contents:
```
# vim: ft=sudoers
#
# This file MUST be edited with `visudo -sf FILENAME`.

Cmnd_Alias APACHE_RESTART = /usr/sbin/service apache2 restart

www-data ALL = NOPASSWD: APACHE_RESTART
```


### www-data crontab entry

It also expects a `www-data` crontab entry:
```
@hourly /var/www/creativecommons.org/scripts/translations-merge-update.sh
```


### Transifex Command

The `translations-merge-update.sh` script contains the following transifex
client command:
```shell
tx -q pull -a --mode developer
```
- **`-q`**
  - *`-q`, `--quiet`: don't print status messages to stdout* (`tx --help`,
    formatting updated)
- **`pull`**
    - *The `tx pull` command lets you download translation files from Transifex
      for use.*
- **`-a`**
  - *`-a` or `--all`: Fetch all translation files from server, even ones which
    don’t exist already locally. If this option isn’t included, only the files
    that exist locally will be updated. If your pull command is skipping files,
    try to add this switch in combination with -f.*
- **`--mode developer`**
    - *`developer`: The files downloaded will be compatible with the i18n
      support of the development framework you’re using. This is the default
      mode when you run tx pull. Use this mode when you intend to use the file
      e.g. in production. This mode auto-fills empty translations with the
      source language text for most of the file formats we support, which is
      critical in the case of file formats that require all translations to be
      non-empty.*


#### Documentation

- [Pull: Download Files | Transifex Documentation](https://docs.transifex.com/client/pull/)
