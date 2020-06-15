# image à utiliser
FROM php:7.3-apache

# Activation du module de réécriture des URLs d'Apache2
RUN a2enmod rewrite

# Argument de build. Sert à créer un utilisateur avec le même UID que l'hôte
ARG UID

# On envoie le nom du serveur à apache, c'est avec ça que l'on appelera nos pages
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Quelques library necessaires (apt, git, net-tools, vim et wget)
RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git net-tools vim wget

# on télécharge et deplace le composer
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

# Installation de l'extention php pdo
RUN docker-php-ext-install pdo pdo_mysql

# Installation des locales
RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

# Configuration de la timezone
RUN echo "Europe/Paris" > /etc/timezone





#############################################################################################
############ Inserez les librairies / extensions supplémentaires dans cette zone ############
#############################################################################################


# Installation de l'extention php zip
RUN apt-get update && apt-get -y install libzip-dev zip \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip

# Installation de yarn
RUN curl https://deb.nodesource.com/setup_12.x | bash \
    && curl https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y nodejs yarn postgresql-client

# Installation de wkhtmltopdf
RUN apt-get update && apt-get -y install libpng-dev libxrender1 libfontconfig1 libfreetype6 fontconfig libjpeg62-turbo xfonts-75dpi xfonts-base libxext6 && \
    curl "https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.5/wkhtmltox_0.12.5-1.stretch_amd64.deb" -L -o "wkhtmltopdf.deb" && \
    dpkg -i wkhtmltopdf.deb

# Installation de l'extension php intl : 
RUN apt-get install -y zlib1g-dev libicu-dev g++ \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Installation de l'extension php imagick :
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/* && \
    printf "\n" | pecl install imagick && \
    docker-php-ext-enable imagick

# Installation de l'extension php GD
RUN apt-get -y install libpng-dev && \
    docker-php-ext-install gd

#############################################################################################
#############################################################################################
#############################################################################################

RUN echo "post_max_size = 50M" >> /usr/local/etc/php/php.ini
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/php.ini




# On créé un utilisateur avec le même gid/uid que votre local
# cela va permettre que les fichiers qui sont créés dans le contenaire auront vos droits
RUN adduser --system --force-badname "symfony" --uid $UID --ingroup "www-data"

# A chaque connexion au container, le répertoire de travail sera /var/www
WORKDIR /var/www/html