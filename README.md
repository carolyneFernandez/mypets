Initialisation du projet Docker : 

**Prérequis :**
- 
- docker
- docker-compose
- yarn (conseillé) [Installer yarn](https://classic.yarnpkg.com/fr/docs/install)

**Lancement du projet :**
- 

Créer le réseau interne dans docker : 
`docker network create isolated_mypets --internal`

Executer la commande suivant à la racine du projet : 
`./init.sh`

Les containers vont se créer tout seul et vous ouvrira un shell dans le container.

Lien le site : http://localhost:8030

Lien vers phpmyadmin : http://localhost:8031

Lien vers MailHog (serveur de réception de mail pour le dev) : http://localhost:8032

**Configuration de l'espace de dev pour Docker**
Créer un fichier `.env.local` puis faites un copier coller des lignes suivantes :

```
APP_ENV=dev

URL=http://localhost:8030

MAILER_URL=smtp://mypets_mailhog:1025  # redirige tous les mails sortant vers le container MailHog

DATABASE_HOST=mypets_db
DATABASE_PORT=3306
DATABASE_NAME=mypets
DATABASE_USER=mypets
DATABASE_PASSWORD=password

DATABASE_URL=mysql://${DATABASE_USER}:${DATABASE_PASSWORD}@${DATABASE_HOST}:${DATABASE_PORT}/${DATABASE_NAME}

```


**Compiler les fichiers JS et SCSS (en css) avec webpack**
- 

[Documentation](https://symfony.com/doc/current/frontend.html#getting-started)

Installation des paquets yarn :

`yarn install`

Pour compiler en dev avec un watcher, lancer la commande suivante : 

`yarn encore dev --watch`

Attention, le watcher ne fonctionne pas à partir du container pour le moment. C'est pour cela qu'il est conseillé d'installé yarn sur la machine hôte, puis lancer la commande directement en dehors du container.


Pour compiler avant le mises en lignes sur les espaces recette et prod : 

`yarn encore prod`

Pourquoi ? Parce que les fichiers générés pour la production auront des noms de fichiers unique à chaque compilation. On parle de versionning. Cela évite au client web de recharger la page en vidant son cache pour récupérer les modifications css et js.  








