# Blogium

## What is it?

Blogium is a basic test project for a selection process.

Blogium has three parts:

1. A public website that has the latest posts of the platform and the pages to register and log in.
2. A secured area with a SPA made with Webpack Encore and Vue.
3. An API with the following endpoints: GET /blogposts, POST /blogposts and GET /blogposts/{id}

## What technologies were used to do it?

1. Docker: There is a docker compose file that creates three containers: MySQL, PHP and Nginx.
2. MySQL 8
3. PHP 8.1
4. Nginx
5. Symfony 6.1.5 (Latest Stable Version)
6. Api Platform 3.0
6. Vue
7. Webpack Encore

## What dependencies were used and why?

1. doctrine/orm: To automatically map the entities and their properties to the database.
2. symfony/security-bundle: To configure the firewalls of the application.
3. symfony/rate-limiter: To protect against brute force attacks to the login form.
4. symfony/validator: To validate that the data sended from the user is correct.
5. symfony/maker-bundle: To ease the development process.
6. symfony/http-client: To send requests to the jsonplaceholder API.
7. symfony/webpack-encore-bundle: To create manager the assets and create the SPA.
8. symfony/twig-bundle: As a template engine to generate dynamic html in the server side and as dependency of api platform.
9. api-platform/core: To create a REST API and automatically expose the documentation.
10. fakerphp/faker: To generate false data for the project in order to feel it more real.
11. phpunit/phpunit: To implement the unit testing.
12. phpstan/phpstan: To scan the codebase to warn about possible problems.
13. lexik/jwt-authentication-bundle: To secure the API through JWT.

And much more that are not so relevant.

## Installation

### Prerequisites

1. Install docker https://www.docker.com/
2. Install docker-compose https://docs.docker.com/compose/install/
3. Install node https://nodejs.org/en/download/
4. Install composer https://getcomposer.org/

### Requisites

Once the prerequisites are installed, open a terminal and execute the following commands:

```
git clone https://github.com/ismav10/blogium
composer install
docker-compose up
npm install
npm run build
npm run dev-server
```

### Use the platform

You can check the `security.yaml` file to check the firewalls of the application.
To access to the api documentation is not necessary to have and user but the endpoints are protected via JWT authentication and a user is needed.
The authentication against the api is made in the same moment that you log into the platform via the login form. 

### Optional steps

The mysql's docker container loads a default dump that has 100 users and 100 posts with the necessary relationships.
This dump was generated just after using a command implemented in the application and described below.
It can be used if is wanted more data or if is wanted data from the jsonplaceholder API.

`db:generate-default-data` is a command that loads dummy data into the database.
This command has two options: 

1. -m indicates the strategy to generate the data:
    'api' gets the data from https://jsonplaceholder.typicode.com/
    'fake' generates the data with faker. Default option.
2. -l indicates the number of elements to insert in the database. It's only used with faker mode.

There is a listener in the project that hashes the password of the users so the generation process can take a while.

The username needs to be unique, so the command always fails if is executed two times with mode 'api' (Due to the fact that the usernames are always the same).

Faker can also produce the same random username for two different users, so there is a possibility of the command to fail due to the same constraint.

## Notes about the project

### Code organization decisions

I've tried to use a hexagonal architecture with the following structure:

```
.
├── Application/    This layer contains the use cases of the application.
│   ├── BlogPost/   Contains the use cases related to blog posts.
│   ├── User/       Contains the use cases related to users.
│   └── Shared/     Contains the use cases that has to do with blog posts and users. In this case holds the data generator used by the CLI.
├── Domain/	    This layer contains the domain classes.
│   ├── BlogPost/   Contains the class that model the blog post and the repository interface.
│   └── User/       Contains the class that model the user and the repository interface.
└── Infrastructure/ Contains the classes for the entry points of the application and the persistence layer.
    ├── Api/
    ├── Command/
    ├── Controller/
    └── Persistence/
```
    
### Problems that I've found

I'm not used to work with webpack-encore or, simply, webpack and I've never worked before with Vue. 
So it took me a while to learn the basics, give it a try and use it to make the SPA.
I didn't want to spend so much time in this part so I did the minimum to get something functional.
I know too that in the statement of the test says that this is optional but I wanted to do it.

Other thing that I'm not used to work with is hexagonal architecture, but I've tried to apply it anyway because
I know that is something that the company uses.

### Things that could be different

1. Instead of implement a command to generate the data I could have relied in Symfony data fixtures, but I wanted it to be configurable from the command line to be able to generate the data in different ways and give support to the jsonplaceholder api.
2. The public part of the application has only the last 10 blog posts, but if you go to the concrete url of a post that is not between the last you can as an anonymous user. 
A voter could be implemented to avoid this problem but the performance would be affected because adds a query to the database to check if the post is between the last and grant the access to anonymous users.
This problem is not minor because the public part is accessed by a lot of bots. 
To avoid calling the database too much times I would use a Redis database as an in-memory cache for some queries.
3. This project doesn't follow the KISS principle at all, but this is because of the nature of it to be a technical test project.
4. There are some features that I would wanted to try to add if this were a real project (Some of them are probably not so easy to implement, are just a little abstract ideas):
    - A logger.
    - L10n and i18n.
    - A subscription system for the users to be notified via different channels.
    - A karma system based on the tries of plagiarism. The SimHash algorithm maybe could help to check if a post is a copy.
    - A popularity system based on the number of subscribers of the user.
    - A personal taste system based on the k-nearest neighbors algorithm with the users that has more common subscriptions in the platform (because there are not other relevant clasifiers as tags or categories).
    - A post recommendation system that takes into account the four features in a weighted way.

### Feedback about the technical test

1. Is simply enough that you don't need to spend too much time.
2. Has a few subtle details to check the knowledge of the developer.
3. The fact that it hasn't limit time gives the developer the freedom to choose the scope of the project to develop.

### Thanks

Thanks in advance for the time that you'll dedicate to check my job and I'll be very thankful of any possible feedback.

#### Just as a curiosity

All the images used in this project were generated with DALL-E.