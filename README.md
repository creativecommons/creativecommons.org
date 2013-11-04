# CC WordPress 2013

Sample Apache config file, place in /etc/apache* and tweak the paths
as needed:

    Include /var/www/creativecommons.org/apache.conf
    
    <VirtualHost *:8080>
        Use CCVHost creativecommons.org http /var/www/creativecommons.org /var/log/apache2/creativecommons.org
    </VirtualHost>
    
    <VirtualHost *:443>
        Use CCVHost creativecommons.org https /var/www/creativecommons.org /var/log/apache2/creativecommons.org
        SSLEngine on
        SSLCertificateFile /etc/ssl/private/creativecommons.org.crt
        SSLCertificateKeyFile /etc/ssl/private/creativecommons.org.key
        SSLCACertificateFile /etc/ssl/certs/RapidSSL_CA_bundle.pem
    </VirtualHost>

You'll need the <code>macro</code> Apache module. On Debian-like systems you can try:

    apt-get install libapache2-mod-macro
    a2enmod macro

There is a setup script, <code>server_bootstrap.sh</code>, in this
checkout, it primarily sets up the python environment. It should "just
work", but if it doesn't then give it a read. It requires some basic
Python utilities like <code>virtualenv</code> and <code>pip</code>.

Happy hacking!