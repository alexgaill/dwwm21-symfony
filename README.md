# test dwwm21 - symfony 

Ce projet est réalisé avec la promotion **test dwwm21** de l'année 2022.

Vous pouvez retrouver le projet sur <https://github.com/alexgaill/dwwm21-symfony>.

## Liens utiles
> [Installer composer](https://getcomposer.org/download/)
>
> [Installer symfony](https://symfony.com/download)
>
> [Documentation Symfony](https://symfony.com/doc/current/index.html)
>
> [Documentation Twig](https://twig.symfony.com/doc/)
>
> [Documentation doctrine](https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/query-builder.html#the-querybuilder)
>
> [FakerPhp](https://packagist.org/packages/fakerphp/faker)
>
> [Documentation FakerPhp](https://fakerphp.github.io/)
>
> [KnpPaginator](https://github.com/KnpLabs/KnpPaginatorBundle)

## Documents de cours
> [Déroulé](documentsCours/deroule.pdf) 

## Pour lancer le projet
```json
{
"require": {
    "php": 8.1.*,
    "composer": 2.*
}
```
```sh
# Lorsque vous récupérez le projet, exécutez les commandes suivantes dans le terminal
composer install #installe les dépendances nécessaires au bon fonctionnement du projet

# Créez le fichier .env.local à la racine du projet avec la ligne DATABASE_URL correspondant à votre serveur Mysql
# DATABASE_URL="mysql://root:@127.0.0.1:3306/superblog" # xampp ou wamp
# DATABASE_URL="mysql://root:root@127.0.0.1:8889/superblog" # mamp

symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
symfony server:start
 ``` 

## Une question sur le projet? 
### [Contactez-moi](mailto:contact@steptosuccess.com)

## Pour aller plus loin vous pouvez:

> ### Rejoindre la communauté sur [discord](https://discord.gg/zDm8RX8jYb)
> Vous serez avertis des dernières news de cours, vous agrandirez la communauté d'entraide d'étudiants et devs junior.
>
> ### Participer aux lives [twitch](https://www.twitch.tv/alex_gaill)
> Live de création et de mise à jour de cours, découvertes de langages et framework, talkshow sur différents sujets (S'améliorer en temps que dev, le freelancing, la recherche d'emploi/stage/alternance)
> ### Suivre la chaine [youtube](https://www.youtube.com/channel/UCgj5orSaIhJ8r7tVT6qjr3Q)
> Voir les dernières rediffusions des live, les modules enregistrés, ...

