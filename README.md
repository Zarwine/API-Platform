# API-Plateforme
Un projet réalisé dans le but d'utiliser API Plateforme dans le cadre d'une application Symfony

# Installation
- Cloner le projet : `git clone git@github.com:Zarwine/API-Plateforme.git`
- Installer les dépendances PHP : `composer install`
- Créer la base de donnée : `php bin/console doctrine:database:create`
- Générer de fausses données : `php bin/console doctrine:migrations:migrate`

# Utilisation
Lancer le serveur de developpement : `symfony server:start`
Lancer les tests avec PHP Unit : `bin/phpunit`
