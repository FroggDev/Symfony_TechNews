I ] 1ere Etape : Installation des packages
==========================================

- dans le project lancer dans une console la commande
composer install


II ] 2eme Etape : Configuration de la base de données
=====================================================

dans le fichier .env à la racine de votre projet

remplacer

### doctrine/doctrine-bundle ###
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"

par

### doctrine/doctrine-bundle ###
DATABASE_URL=mysql://root:@127.0.0.1:3306/technews

si vous avez une configuration mysql differente vous pouvez modifier en fonction des besoins


III ] 3eme Etape : Creation de la base de données
=================================================

- si vous avez déjà une base de donnée avec le nom technews, supprimez la (drop database)

- dans le project lancer dans une console la commande
php bin/console doctrine:database:create


IV ] 4eme Etape : Creation de la structure de la base de données
================================================================

- dans le project lancer dans une console la commande afin de verifier que tout soit ok
php bin/console doctrine:migrations:diff

- Puis pour exécuter les requètes de creation de la structure :
php bin/console doctrine:migrations:migrate


V ] 5eme Etape : Importation des donnée dans la base de données
================================================================

- Executez le contenu du fichier dans un executeur de requete sql (dans phpMyAdmin ou autre outil de base de donnée)
/!\ verifiez bien la base dans la quelle vous vous trouvez aveant d'executer la requete. /!\

test/ContenuDeDemo.sql


VI ] Bonus : Pour ajouter des données suplémentaires
====================================================

- pour des tests plus poussé, il est possible d'ajouter des articles/auteurs/categories dans la base
- vous pouvez lancer la commande :
php bin/console app:create-db-entry

- cette commande est une commande ajoutée manuellement (ce n'est pas une commande symfony)
pour editer le contenu des données entrées en base, il faut modifier le fichier du projet :
src/Command/CreateDatabaseEntry.php


VII Felicitation !
==================

- Si toutes les étapes ont été reussie avec succes le site devrait être opérationnel !
pour le tester vous pouvez lancer la commande :
php bin/console server:run

- puis visitez le site à l'adresse :
http://127.0.0.1:8000

- pour visualiser les routes vous pouvez utiliser la commande
php bin/console debug:router