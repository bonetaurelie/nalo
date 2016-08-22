#NALO - OPENCLASSROOMS PROJET 6

Application pour le projet 6 du parcours "Chef de projet multimédia" de [OpenClassRooms](https://openclassrooms.com)

Elle a pour but de mettre en place un site fonctionnel sur le thème de l'ornithologie, permettant l'ajout et la lecture d'observations d'espèces d'oiseaux.

Cette application est faite sous Symfony 3 en Framework back avec Twitter Bootstrap 3 en Framework front 

##Prérequis

Serveur PHP 5.6 mininum, Apache 2.4, MySql 5.6 et Linux (Ubuntu 14.04 ou 16.04)

##Installation

En ligne de commande (Attention faut avoir installé [GIT](https://git-scm.com/)) : 
dans le répertoire du projet, taper : `git clone https://github.com/bonetaurelie/nalo.git`

Puis faire un composer install :

Si composer n'est pas installé : installer et suivre la procédure d'installation de [composer](https://getcomposer.org/)

Puis dans la console dans le répertoire du projet taper : `composer install` 
ou `php [chemin où se trouve composer.phar}/composer.phar install` si composer n'est pas installer en global sur la machine

##Configuration

En plus des paramètrages de base de Symfony 3, 6 paramètres supplémentaires ont été ajoutés :

1. robot_email: Adresse e-mail qui partira du site
2. contact_email: Adresse e-mail de destination du formulaire de contact
3. sender_name: Le nom donné à l'envoyeur de l'e-mail robot (le nom qui s'affiche dans la boite au lettre à la place de l'e-mail du destinataire
4. google_recapatcha_site_key: La clef "site" (celle qui est visible côté public du site) pour l'utilisation du système anti-robot de Google
4. google_recapatcha_secret_key: La clef "secret" (celle qui est non visible qui sera utilisé côté serveur) pour l'utilisation du système anti-robot de Google
5. google_map_key: La clef nécessaire à l'utilisation de l'API de Google MAP

## Jeux de données pour démonstration et/ou test

Dans la racine de l'application taper : `php bin/console doctrine:fixtures` 

Ces jeux de données mettent en place :

1. 2 utilisateurs :
    1. un compte amateur : tesst.amateur@test.fr / test1A-
    2. un compte professionnel : tesst.professionnel@test.fr / test1P-
2. Une liste des oiseaux connus
3. la liste des départements et des villes françaises.
4. une liste aléatoire de 20 observations situés dans différentes villes avec différents status attribuées au compte amateur ci-dessus.

Ces jeux de données se chargent via des fixtures qui se trouvent dans le dossier /src/AppBundle/DataFixtures/ORM/

## Todo list / Reste à faire

* Finir les tests fonctionnels et unitaires
* Commenter le code
* Améliorer le code

## Crédits

Utilisation des Frameworks, bundles, API et plugins suivant :

### Frameworks :

 * Framework PHP : [Symfony 3](http://symfony.com/)
 * Framework HTML / CSS / JS : [Twitter Bootstrap 3](http://getbootstrap.com/)
 
### Bundles :
 
 * [FOSUserBundle](https://github.com/FriendsOfSymfony/FOSUserBundle)        : Pour la gestion des utilisateurs
 * [EWZRecaptchaBundle](https://github.com/excelwebzone/EWZRecaptchaBundle)   : Pour l'utilisation du système Anti-robot de Google
 * [DependentFormsBundle](https://github.com/anacona16/DependentFormsBundle) : Pour l'utilisation d'un sytème de sélection en cascade (ex : Département -> ville)
 * [KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)   : Pour l'utlisation d'un système de pagination
 * [VichUploaderBundle](https://github.com/dustin10/VichUploaderBundle)  : Pour l'utilisation d'un système d'enregistrement d'image
 * [LiipImagineBundle](https://github.com/liip/LiipImagineBundle)    : Pour facilement gérer les différentes tailles d'images qui sont utilisées dans le site.
 
### APIs :
 
 * [Google Recaptcha](https://www.google.com/recaptcha/intro/index.html)  : Système anti-robot, utilisé dans le formulaire de la page contact
 * [Google Map](https://developers.google.com/maps/?hl=fr)                : Affichage d'une carte de France avec prise en compte des coordonnées GPS, permet de récupérer soit le lieu dit à partir d'une position sur la carte, soit de récupérer les coordonnées GPS en fonction d'un lieu dit donné.
 
### plugins Jquery :
 
 * [datetimepicker](https://github.com/xdan/datetimepicker)   : Permet une saisie facile de la date et de l'heure pour une observation
 * [bootbox](http://bootboxjs.com/)                           : Permet d'utiliser les modals de Twitter Bootstrap pour les boites d'alertes/Confirmation/Dialogue
 * [lightbox2](http://lokeshdhakar.com/projects/lightbox2/)   : Ajout un système de diaporama en Javascrip pour lire les photos des observations 