Budget request REST API [![Build Status](https://travis-ci.org/msalsas/budget-request.svg?branch=master)](https://travis-ci.org/msalsas/budget-request)
==========================

Budget request is a REST API made with Symfony.

Documentation
-------------

    GET /budget
    example: GET /budget
    Description: Get all budgets paginated
    
    GET /budget/{email}
    example: GET /budget/johnDoe@email.com
    Description: Get all budgets paginated by email

    POST /budget
    parameters: json => {title, description, category, email, telephone, address}
    example: POST /budget
    Description: Create a budget

    PUT /budget/{id}
    parameters: json => {title, description, category}
    example: PUT /budget/32
    Description: Update a budget

    PUT /budget/publish/{id}
    example: PUT /budget/publish/32
    Description: Publish a budget
    
    PUT /budget/discard/{id}
    example: PUT /budget/publish/32
    Description: Discards a budget

Installation
------------

    git clone https://github.com/msalsas/budget-request.git

    cd budget-request
    
    cp .env .env.local
    
Set DATABASE_URL variable in .env.local to your needs (db_user and db_password, as well as db_name)

    composer install

    php bin/console doctrine:database:create

    php bin/console doctrine:database:create --env=test
    
    php bin/console doctrine:migrations:migrate

    php bin/console doctrine:migrations:migrate --env=test

    symfony server:start 
    
*you will need:*
 
 *- The [Symfony installer](https://symfony.com/download)*
 
 *- `php-sqlite3` as database for testing is sqlite by default*

Testing
=======

    php ./bin/phpunit

License
-------

This bundle is under the MIT license. See the complete license [in the bundle](LICENSE)

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/msalsas/budget-domain/issues).
