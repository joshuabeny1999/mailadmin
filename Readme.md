# Mailadmin
Mailadmin is a PHP-Application I created for my very first Mail-Server I had setup  back in 2016 during my apprenticeship as a computer scientist specializing in system engineering. I used following tutorial: [Mailserver-Anleitung von Thomas Leister.](https://legacy.thomas-leister.de/sicherer-mailserver-dovecot-postfix-virtuellen-benutzern-mysql-ubuntu-server-xenial/). Addidionally I used the [Materiallize-CSS Framework](https://github.com/Dogfalo/materialize) for the design

This Mailserver setup use for the account management a easy MySQL-DB. 

Recently I did a Server-Upgrade and switched to [Mailcow Dockerized](https://github.com/mailcow/mailcow-dockerized) for my current Mailserver. I recommend it. But if you did too setup your Mail-Server with this tutorial or have another setup wich using a MySQL-DB for user management, feel free to use it. It shoud work with PHP 7.0, else feel free to update it.

## Installation
You need a LAMP-Stack or equally to run Mailadmin. The PHP-Extension `mysqli` is needed. Additionally, I recommend you to restrict the access to the application via Apache-Authentication (.htaccess-File).
