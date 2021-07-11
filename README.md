## For Backend Assessment
Technology to be used:
- PHP
- Redis
- MySQL
- Choose a framework that you are familiar with. (Laravel)

## Requirements
Create PHP API project that has an API endpoint that takes a list of github usernames (up to a maximum of 10 names) and returns to the user a list of basic information for those users including: 
- Name 
- Login 
- Company 
- Number of followers 
- Number of public repositories 
- The average number of followers per public repository (ie. number of followers divided by the number of public repositories) 
In order to access the API endpoint described above, another endpoint should be created for user registration and login. 
Rules to Follow: 
- Schema for User registration should be created in MySQL 
- Only registered users can request a list of GitHub user information. 
- The returned users should be sorted alphabetically by name 
- If some usernames cannot be found, this should not fail the other usernames requested - Implement a caching layer using Redis that will store a user that has been retrieved from GitHub for 2 minutes 
- Each user should be cached individually. For example, if I request users A and B, then do another request inside 2 minutes for users B, C and D, user B should come from the cache and users C and D should come from GitHub - If a user is returned from the cache, it should not call GitHub API 
- The API endpoint needed to get github user information is 
https://api.github.com/users/{username} 
- Include proper error handling 
- Include proper logging 
- Provide a Readme.md with instructions on how to execute your API endpoint

## How to setup
- Create a database locally named `kumu_test` 
- If composer is not yet installed. Download composer https://getcomposer.org/download/
- Copy `.env` inside your project root and fill the database information.
  - Update env file for MySQL config:
    DB_HOST=<host>
    DB_PORT=<port>
    DB_USERNAME=<database username>
    DB_PASSWORD=<database password>
  - Update env for Redis config:
    REDIS_HOST=<host>
    REDIS_PASSWORD=<password>
    REDIS_PORT=<port>
- Open the console and cd your project root directory
- Run `composer install` or ```php composer.phar install```
- Run `php artisan key:generate` 
- Run `php artisan migrate`
- Run `php artisan serve`

## How to call api endpoints
- Registration endpoint:
`curl --location --request POST 'http://localhost:8000/api/register' \
--header 'Accept: application/json' \
--form 'name="Kumu"' \
--form 'email="kumu_test@dummygmail.com"' \
--form 'password="password123"'`

- Login endpoint:
`curl --location --request POST 'http://localhost:8000/api/login' \
--header 'Accept: application/json' \
--form 'email="kumu_test@dummygmail.com"' \
--form 'password="password123"'`

- Get Github users:
`curl --location --request POST 'http://localhost:8000/api/get-user-details' \
--header 'Accept: application/json' \
--header 'Authorization: Bearer <token from login>' \
--header 'Content-Type: application/json' \
--data-raw '["andrew","fabpot","nelsonic","taylorotwell","egoist","ornicar","bebraw","HugoGiraudel","alexcrichton","jonathanong"]'`


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
