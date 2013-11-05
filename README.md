# CC WordPress 2013

Sample Apache config file, place in /etc/apache* and tweak the paths
as needed:

    Include /var/www/creativecommons.org/apache.conf
    
    <VirtualHost *:8080>
        Use CCVHost creativecommons.org http /var/www/creativecommons.org /var/log/apache2/creativecommons.org
	UseCanonicalName On
    </VirtualHost>
    
    <VirtualHost *:443>
        Use CCVHost creativecommons.org https /var/www/creativecommons.org /var/log/apache2/creativecommons.org
	UseCanonicalName On
        SSLEngine on
        SSLCertificateFile /etc/ssl/private/creativecommons.org.crt
        SSLCertificateKeyFile /etc/ssl/private/creativecommons.org.key
        SSLCACertificateFile /etc/ssl/certs/RapidSSL_CA_bundle.pem
    </VirtualHost>

The <code>UseCanonicalName</code> option forces URLs to redirect to
the canonical host name, you can remove it if you want to use other
names to access the site (e.g., an Amazon EC2 hostname before you get
DNS set-up).



You'll need the <code>macro</code> Apache module. On Debian-like systems you can try:

    apt-get install libapache2-mod-macro
    a2enmod macro

There is a setup script, <code>server_bootstrap.sh</code>, in this
checkout, it primarily sets up the python environment. It should "just
work", but if it doesn't then give it a read. It requires some basic
Python utilities like <code>virtualenv</code> and <code>pip</code>.

To configure WordPress, there is a sample config file at
<code>docroot/wp-config-local.php.sample</code>, copy it to
<code>docroot/wp-config-local.php</code> and fill in the information
as needed by the WP install.

Happy hacking!