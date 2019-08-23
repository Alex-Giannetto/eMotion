### Git
```bash
git clone git@github.com:Alexg78bis/eMotion.git
```

### Docker

Créer un fichier `docker-compose.yml` dans le dossier parent de `eMotion` et y placer la configuration présente plus bas
```bash
docker-compose up
```

### Dépendances
Installation des dépendances
```bash
composer install
```

### Base de données
- Créer la base de données
    ```
    docker-compose exec web php bin/console doctrine:database:create
    ```

- Créer les tables
    ```
    docker-compose exec web php bin/console doctrine:schema:update --force
    ```

- Créer un jeu de données
    ```bash
    docker-compose exec web php bin/console doctrine:fixtures:load
    ```
    



## docker-compoose.yml

version: '3.6'
services:
    web:
        image: webdevops/php-nginx:7.3
        working_dir: /app
        ports:
            - 80:80
        depends_on:
            - database
        volumes:
            - ./eMotion/:/app:cached
        environment:
            WEB_DOCUMENT_ROOT: /app/public
            WEB_DOCUMENT_INDEX: index.php
            PHP_DATE_TIMEZONE: "Europe/Paris"
            PHP_DISPLAY_ERRORS: 1

    adminer:
        image: 'adminer:4.7'
        ports:
            - '8080:8080'
        depends_on:
            - database
    database:
        image: 'mariadb:10.3'
        ports:
            - '3306:3306'
        environment:
            - MYSQL_ROOT_PASSWORD=root
