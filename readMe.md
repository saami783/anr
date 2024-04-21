# CityConnect, test technique anr avec Symfony 6.4

## Liste des fonctionnalités de l'application :

- Système d'authentification et d'inscription.
- Système de compte administrateur sans bundle.
- Mot de passe oublié avec lien de réinitialisation par mail.
- Upload et suppression d'image de profil.
- Image de profil par défaut imposé si l'utilisateur n'en a pas.
- CRUD des entités `Ville` et `Rue` -> Modification d'état uniquement pour les administrateurs.
- Recherche d'utilisateur par `ville` ou par `rue`.
- Modification de ses données personnelles.
- Suppression de son compte utilisateur.

## Mise en route

L'application a besoin d'une version de PHP >= 8, de composer et npm (j'ai utilisé la v10.2.4).
La base de données doit être sous MySQL.
***

## Installer les dépendances du projet :

Le fichier .env.local n'est pas inclus dans le dépôt GitHub pour des raisons de sécurité.
Assurez-vous de configurer vos variables d'environnement. <br>
Pensez également à configurer votre serveur SMTP dans le fichier.

```
composer install
npm install
npm run build
```
***

# Démarrer le serveur web

Pour lancer le serveur web, veuillez exécuter les commandes suivantes :

```
php bin/console assets:install
symfony serve -d
```

# Importation de la base de données MySQL
Pour importer la base de données gérée par Doctrine, suivez les étapes ci-dessous.

## Création de la base de données
Créez une nouvelle base de données nommée `anr` en exécutant la commande suivante.

```
php bin/console doctrine:database:create
```

## Exécution des commandes d'importation
Exécutez ensuite les commandes suivantes pour importer les tables :

```
php bin/console make:migration
php bin/console d:m:m
```

## Importation des données

Vous devez ensuite importer les données liées aux entités `Ville` et `Rue`. <br>
Cela est nécessaire pour l'inscription d'un utilisateur qui éxige de devoir remplir une adresse avec les entités `ville` et `rue` qui doivent être préalablement existants. <br>
Pour ce faire, rendez-vous sur votre système de gestion de base de données et importez les fichiers d'insertion du dossier `sql` dans l'ordre suivant :

1. ville.sql
2. rue.sql

***

## Accédez à l'application

Une fois ces étapes terminées, votre base de données sera prête à être utilisée avec l'application web. <br>
Rendez-vous à l'adresse suivante :`https://127.0.0.1:8000`. <br>


Notes :

- Pour éviter les insert sql j'ai intégré un crud pour les entités `Ville` et `Rue`.
- Un administrateur a le rôle ROLE_ADMIN tandis qu'un utilisateur a le rôle ROLE_USER.
- L'application utilise le bundle VichUploader pour la gestion des images.
- Chaque image est enregistrée dans le dossier `public/uploads/avatar/`. <br>
- Le rendu des images est géré par le bundle LiipImagine.
