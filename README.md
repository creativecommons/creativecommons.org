# CC WordPress 2013

## Set-up

There are some handy setup scripts in the <code>scripts</code>
directory. On a fresh Ubuntu 12 server, you should only need to run
<code>scripts/bootstrap.sh</code>, which calls other scripts to set
most things up. See below for some details on the other scripts.

You'll still need to manually configure a few things:

1. Edit <code>docroot/wp-config-local.php</code> and fill in values as
   needed by WordPress.
2. Set up SSL keys in /etc/ssl/*
3. Edit /etc/apache2/sites-available/<hostname> if needed (e.g. to
   change SSL key locations)
4. Load DB data from another WordPress install (see below)

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
  Ubuntu 12 server (install packages and enable apache modules, etc).

* <code>scripts/bootstrap_mysql.sh</code>: Creates a database and user
  with specified password. Use these values in your
  <code>wp-config-local.php</code> file (see below).

* <code>scripts/bootstrap_checkout.sh</code>: Sets up this checkout by
  downloading git submodules, creating a virtual Python environment,
  etc.

Happy hacking!