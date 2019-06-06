## translations-merge-update.sh

This script requires `/etc/sudoers.d/translations-merge-update` with contents:
```
# vim: ft=sudoers
#
# This file MUST be edited with `visudo -sf FILENAME`.

Cmnd_Alias APACHE_RESTART = /usr/sbin/service apache2 restart

www-data ALL = NOPASSWD: APACHE_RESTART
```

It also expects a `www-data` crontab entry:
```
@hourly /var/www/creativecommons.org/scripts/translations-merge-update.sh
```
