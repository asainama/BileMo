# Bilemo

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/f9e6630025224624a2ceade608e388e6)](https://www.codacy.com/gh/asainama/BileMo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=asainama/BileMo&amp;utm_campaign=Badge_Grade)

## Environnement

* Symfony 5.2
* Composer 2.0.7
* PHP 7.2.1
* MYSQL  8.0.19
* jms/serializer-bundle 3.9
* lexik/jwt-authentication-bundle 2.11
* nelmio/api-doc-bundle 4.2
* pagerfanta/pagerfanta 2.7
* FosRestBundle 3.0
* fakerphp/faker 1.14
* willdurand/hateoas-bundle 2.2

## Installation

1. Cloner le répertoire

```
    git clone https://github.com/asainama/bilemo.git

    composer install
```

2. Configurer le env.local

Créer un fichier .env.local qui devra avoir:

```
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/bilemo?serverVersion=5.7"
```

Il est important de déplacer la partie JWT_PASSPHRASE dans le .env.local

3. Mettre en place JWT
4. 
```
    mkdir config\jwt
    openssl genrsa -out config/jwt/private.pem -aes256 4096
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Une paraphrase sera demander, il faudra pour cela copier celle de JWT_PASSPHRASE

4. Installer la base de données

```
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
```

### Fixtures

Il est possible de remplir la base de données avec des données de test grâce à la commande

```
    php bin/console doctrine:fixtures:load
```

Un utlisateur avec les identifiants:

```
    test@test.fr
    admin
```

5. Félicitations l'api est installé

Vous pouvez consulter la documentation via le lien /api/doc