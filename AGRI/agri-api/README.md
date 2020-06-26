# Symfony 4.1

Template pour les projets symfony 4.1
* Version 4.1.*

Utiliser le composer.phar inclus dans le dépôt pour toutes les opérations de composer
* php composer.phar .....


Packages supplémentaires:
* annotations
* profiler
* maker, pour le commande php bin/console make:...
* orm
* cors, https://github.com/nelmio/NelmioCorsBundle, configuration dans config/packages/nelmio_cors.yaml
* security
* Swiftmailer
* friendsofsymfony/user-bundle
* twig
* lexik/jwt-authentication-bundle, https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#getting-started
* translation
* monolog
* uuid

Packages dev
* symfony/phpunit-bridge
* symfony/browser-kit
* symfony/css-selector
* server
* phpmd/phpmd
* phpstan
* phpmetrics

Variables d'environnement
* APP_ENV
* APP_SECRET
* DATABASE_URL
* CORS_ALLOW_ORIGIN
* MAILER_URL (swift mailer)
* JWT_SECRET_KEY (jwt)
* JWT_PUBLIC_KEY (jwt)
* JWT_PASSPHRASE (jwt)


## Serveur local

Lancer le serveur avec :
* le serveur interne de php: php -S localhost:8000 -t ./public 
* ou le serveur de symfony: php bin/console server:start


## ORM / FOS User
* Create database: php bin/console doctrine:database:create
* Update database: php bin/console doctrine:schema:update --force
* Command line:
    - Ajout super admin: php bin/console fos:user:create admin --super-admin
    - cf: https://symfony.com/doc/master/bundles/FOSUserBundle/command_line_tools.html


## JWT
Line utiles:
* Pour la génération des clé et la confiuration de JWT: https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#configuration
* Pour créer le user provider: http://symfony.com/doc/current/security/custom_provider.html
* Endpoint 

```
POST /api/login_check HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Cache-Control: no-cache
Postman-Token: ccef44ba-af88-435f-8e74-daf3351e27e0

{"username":"demo","password":"demo"}
```

* Ajout custom data dans le payload: App\Security\WebserviceUserProvider->getCustomPayload


## Test

* Lancer les pretests avec (analyse statique)
    - Phpstan: vendor/bin/phpstan analyse src --level 7
    - Phpmd: vendor/bin/phpmd src/ text cleancode,codesize,controversial,design,unusedcode
        - Liste des ruleset : https://phpmd.org/rules/index.html
        - cleancode,codesize,controversial,design,naming,unusedcode
* Pour lancer les tests
    - php bin/phpunit --coverage-text --testdox
    - au premier lancement composer installera les dépendances nécessaires
    - Pour générer le metrique Crap: php bin/phpunit --coverage-html ./crap
* Pour générer phpmetrics: vendor/bin/phpmetrics --report-html=phpmetrics ./src/

## Liens utils
* Test
    - https://symfony.com/doc/current/testing.html
    - https://symfony.com/doc/current/create_framework/unit_testing.html
    - Pour tester les repository: https://symfony.com/doc/current/testing/doctrine.html
    - Pour les mock des entity manager et repo: http://symfony.com/doc/current/testing/database.html
    - Liste des outils de QA: https://web-techno.net/code-quality-check-tools-php/

* FOSUser issue : 
    - https://stackoverflow.com/questions/47844967/symfony-4-fosuserbundle
    - https://symfony.com/doc/current/security.html
    - https://symfony.com/doc/master/bundles/FOSUserBundle/index.html
    - https://vfac.fr/blog/how-install-fosuserbundle-with-symfony-4

