## Requirements

To run this application locally, make sure you have the following installed:

- Docker
- PHP 8.2
- Composer
- PostgreSQL 

## Installation

### 1: Clone the repository

```bash
git clone https://github.com/K3bycz/BlogAPI
```

### 2: Install dependencies

```bash
composer install
```

### 3. Copy environment variables

```bash
cp .env.example .env
```
Make sure to configure your database settings (e.g., DATABASE_URL) in the .env file.

### 4. Set up the database

Ensure that your database is running. Then, execute the following commands to create the database and load the data:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```
### 5. Set directory permissions

If the application has trouble writing to directories such as var or public, ensure that you have the proper permissions. You can do this by running the following commands:

```bash
sudo chown -R www-data:www-data var public
sudo chmod -R 775 var public
```

### 6. Run the application

Using Docker, start the containers with:

```bash
docker compose up -d
```
The application will be available at http://localhost:8080

### 7. Run PHPUnit Tests

After setting up the application, it's a good idea to run the PHPUnit tests to ensure everything is working properly. To do this, execute the following command:

```
php vendor/bin/phpunit
```
This will run all the test cases defined in the tests directory. If everything is set up correctly, the tests should pass. If any tests fail, check the error messages for more details on what might be causing the issue.

## API Documentation

You can test the API endpoints by clicking the button below to run the collection in Postman:

[<img src="https://run.pstmn.io/button.svg" alt="Run In Postman" style="width: 128px; height: 32px;">](https://app.getpostman.com/run-collection/32708062-01c895ce-0d7d-42ff-b8a6-f421c3c323d5?action=collection%2Ffork&source=rip_markdown&collection-url=entityId%3D32708062-01c895ce-0d7d-42ff-b8a6-f421c3c323d5%26entityType%3Dcollection%26workspaceId%3D12f60be8-d681-43c6-b630-cc0451a2b8ee)

## Available Endpoints

Here are some of the available API endpoints:

- POST /api/login_check - Login and get a JWT token
- GET /api/blog/posts - Get all blog posts
- GET /api/users - Get information about users in the system
- POST /api/blog/create - Create a new blog post
- DELETE /api/blog/delete/{id} - Delete a blog post

Enjoy!
