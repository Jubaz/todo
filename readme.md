# TODO

this is a simple api by laravel 5.5

## Installation

### Step 1

> To run this project, you must have PHP 7 , git , composer installed as a prerequisite.

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash

git clone https://github.com/abdomf/todo.git

cd todo

composer install




```
change your mysql database setting in .env.example and remove .example 


### Step 2

```bash

php artisan migrate --seed

php artisan passport:install

php artisan key:generate

php artisan serve

```

api is running now you can test it with some urls via post man :

### user urls

>1- to register user send POST request "127.0.0.1:8000/api/register" with header Accept => "application/json" && Body { name , email , password , c_password } please save your token

>2- to login send POST request "127.0.0.1:8000/api/register" with header Accept => "application/json" && Body { email , password } please save your token

>3- to get user details sed GET request "127.0.0.1:8000/api/user/details" with header Accept => "application/json" , Authorization => "Bearer {token}"

### list urls

>1- to get all lists send GET request "127.0.0.1:8000/api/list"

>2- to get list details send GET request to "127.0.0.1:8000/api/list/{listID}/details"

>3- to create list send POST request "127.0.0.1:8000/api/list/create"  with header Accept => "application/json" , Authorization => "Bearer {token}" && body { title }

>4- to edit list send POST request  "127.0.0.1:8000/api/list/{listID}/edit"

>5- to delete list send DELETE request  "127.0.0.1:8000/api/list/{listID}/delete"

### item urls

>1- to get all items send GET request to "127.0.0.1:8000/api/item"

>2- to get all items in list send GET request "127.0.0.1:8000/api/list/{listID}"

>3- to create item in list send POST request "127.0.0.1:8000/api/list/{listID}/item" with header Accept => "application/json" , Authorization => "Bearer {token}" && body { title , description }

>4- to edit item in list send POST request "127.0.0.1:8000/api/list/{listID}/item/{itemID}/edit" with header Accept => "application/json" , Authorization => "Bearer {token}" && body { title , description }

>5- to delete item in list send DELETE request "127.0.0.1:8000/api/list/{listID}/item/{itemID}/delete" with header Accept => "application/json" , Authorization => "Bearer {token}"

HINT : you must be authenticated to access (create) method and the owner to access ( update , delete )


