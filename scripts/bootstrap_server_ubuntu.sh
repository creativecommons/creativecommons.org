#!/bin/sh

HOSTNAME=${1:-creativecommons.org}
TOPDIR="$( cd "$( dirname "${BASH_SOURCE[0]}/.." )" && pwd )"

apt-get -y install apache2 python-virtualenv python-pip gcc python-dev libxml2 libxml2-dev libxslt1.1 libxslt-dev php5 php5-mysql python-librdf libapache2-mod-fcgid libapache-mod-macro mysql-server mysql-client

for i in macro php5 rewrite ssl fcgid
do
    a2enmod $i
done

if grep -q "apache.conf" /etc/apache2/httpd.conf
then
    echo "Note: /etc/apache2/httpd.conf seems to be loading an apache.conf file,"
    echo "leaving it alone. If that's not the CC apache.conf file, then you'll"
    echo "need to add the Include line manually."
else
    echo "Include ${TOPDIR}/apache.conf" >> /etc/apache2/httpd.conf
fi

cat <<EOF > /etc/apache2/sites-available/${HOSTNAME}
<VirtualHost *:8080>
    Use CCVHost ${HOSTNAME} http ${TOPDIR} /var/log/apache2/${HOSTNAME}
</VirtualHost>

<VirtualHost *:443>
    Use CCVHost ${HOSTNAME} https ${TOPDIR} /var/log/apache2/${HOSTNAME}
    SSLEngine on
    SSLCertificateFile /etc/ssl/private/${HOSTNAME}.crt
    SSLCertificateKeyFile /etc/ssl/private/${HOSTNAME}.key
    SSLCACertificateFile /etc/ssl/certs/RapidSSL_CA_bundle.pem
</VirtualHost>
EOF

mkdir /var/log/apache2/${HOSTNAME}
chown root.adm /var/log/apache2/${HOSTNAME}
chmod 750 /var/log/apache2/${HOSTNAME}

a2ensite ${HOSTNAME}

service apache2 restart
