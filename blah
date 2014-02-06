# CC WordPress 2013

## Set-up

There are some handy setup scripts in the <code>scripts</code>
directory. On a fresh Ubuntu 12 server, you should only need to run
<code>scripts/bootstrap.sh</code>, which calls other scripts to set
most things up. See below for some details on the other scripts.

Note that one of the scripts is run with sudo. If you don't have sudo
enabled for this user, then you'll need to run the scripts manually.

Regardless of how you run the setup scripts, you'll still need to
manually configure a few things:

1. Edit <code>docroot/wp-config-local.php</code> and fill in values as
   needed by WordPress.
2. Set up SSL keys in /etc/ssl/private. If there isn't a key for the
   hostname being set-up, then the Apache config will not include an
   SSL virtual host. Simply re-run the setup script if you add a key.
3. If desired, load DB data from another WordPress install (see below)

### Editing the Apache config

If you need to edit the Apache config, the files are:

* <code>apache.conf</code>: CC Apache config in macro form.
* <code>/etc/apache2/httpd.conf</code>: Includes the
  <code>apache.conf</code> file in this checkout.
* <code>/etc/apache2/sites-available/<hostname></code>: Virtual host
  definition which uses a macro defined in <code>apache.conf</code> to
  pull in a lot of rules and settings. This file gets overwritten by
  the setup script, so if you edit it, do not run it again.

### Loading DB data

If you want to import DB data from another WordPress install, use the
<code>mysqldump</code> / <code>mysql</code> utilities to create a
backup and restore it here:

    # on the source machine:
    mysqldump -u root dbname | gzip > backup.sql.gz
    
    # ... copy (eg. with scp) ...
    
    # then on this machine:
    zcat backup.sql.gz | mysql -u root -p dbname

### Bootstrap scripts (details)

Note that the <code>scripts/bootstrap.sh</code> script calls these for
you. But if something goes wrong / you want to know more:

* <code>scripts/bootstrap_server_ubuntu.sh</code>: Will configure an
  Ubuntu 12 server (install packages, set up apache, set up
  mysql). Should be run as root.

* <code>scripts/bootstrap_checkout.sh</code>: Sets up this checkout by
  downloading git submodules, creating a virtual Python environment,
  etc. Should be run as this user (does not touch system files).

Happy hacking!
