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

## API Documentation

You can test the API endpoints by clicking the button below to run the collection in Postman:

<div class="postman-run-button"
    data-postman-action="collection/fork"
    data-postman-visibility="public"
    data-postman-var-1="32708062-01c895ce-0d7d-42ff-b8a6-f421c3c323d5"
    data-postman-collection-url="entityId=32708062-01c895ce-0d7d-42ff-b8a6-f421c3c323d5&entityType=collection&workspaceId=12f60be8-d681-43c6-b630-cc0451a2b8ee"></div>
<script type="text/javascript">
  (function (p,o,s,t,m,a,n) {
    !p[s] && (p[s] = function () { (p[t] || (p[t] = [])).push(arguments); });
    !o.getElementById(s+t) && o.getElementsByTagName("head")[0].appendChild((
      (n = o.createElement("script")),
      (n.id = s+t), (n.async = 1), (n.src = m), n
    ));
  }(window, document, "_pm", "PostmanRunObject", "https://run.pstmn.io/button.js"));
</script>

## Available Endpoints

Here are some of the available API endpoints:

POST /api/login_check - Login and get a JWT token
GET /api/blog/posts - Get all blog posts
GET /api/users - Get information about users in the system
POST /api/blog/create - Create a new blog post
DELETE /api/blog/delete/{id} - Delete a blog post

Enjoy!
