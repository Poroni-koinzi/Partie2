## Pré-requis

Installer :
    - PHP
    - Composer
    - Apache ou MYSQL
    - Symfony

## Pour lancer le programme

- Tout abord il faut installer les librairies : "composer install"
- Renseigner dans le fichier .env les information de la connexion à la base de donnée.
- Lanser la commande pour créer la base de donnée : "php bin/console doctrine:database:create" 
- Après lancer la commande pour faire la migration : " php bin/console make:migration" suivi de : " php bin/console doctrine:migrations:migrate"

- Pour terminer on lance "symfony serve" ou "php bin/console server:run"
