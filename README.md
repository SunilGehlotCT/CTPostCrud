# Laravel CRUD with User Authentication, Posts, and Comments

This is a Laravel 10 project with user authentication, post creation (with title, description, and image), and comments. It includes pagination, post slug, soft delete, post status (active/inactive), and comment deletion functionality.

## Features

- User authentication (login, registration)
- Dashboard displaying all posts with pagination
- Create, view, update, and delete posts (CRUD operations)
- Toggle post status (active/inactive)
- Soft delete for posts
- Slug generation for posts
- Comment on posts after login
- Display all comments on the post detail page, sorted by newest first
- Pagination for comments
- Delete own comments

## Requirements

- PHP >= 8.0
- Composer
- Laravel 10
- MySQL or another supported database
- Core Bootstrap CDN and jQuery (No NPM/Vite is used)

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/SunilGehlotCT/LarevelCrud.git
    ```

2. Navigate to the project directory:
    ```bash
    cd LarevelCrud
    ```

3. Install PHP dependencies using Composer:
    ```bash
    composer install
    ```

4. Create a copy of `.env`:
    ```bash
    cp .env.example .env
    ```

5. Generate an application key:
    ```bash
    php artisan key:generate
    ```

6. Configure your `.env` file with your database and email settings:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_user
    DB_PASSWORD=your_database_password
    ```

7. Run the database migrations to create the necessary tables:
    ```bash
    php artisan migrate
    ```

8. Start the development server:
    ```bash
    php artisan serve
    ```

9. You can now access the application at `http://localhost:8000`.

## Functionality

### 1. **User Authentication**
- Users can register, log in, and log out using the built-in Laravel authentication system.

### 2. **Post Creation and Management**
- After logging in, users can:
    - Create new posts (with title, description, and image).
    - Edit and delete their own posts.
    - Posts have a `slug`, soft delete functionality, and an active/inactive flag.
    - Posts can be toggled between active and inactive.
    - All posts are displayed on the dashboard, with pagination.
    - Posts can be clicked to view their details, including comments.

### 3. **Soft Delete for Posts**
- Posts can be soft-deleted, meaning they can be restored later if needed.

### 4. **Comments**
- Users can comment on posts after logging in.
- Comments are paginated on the post detail page.
- Users can delete their own comments.

### 5. **Post Slug**
- When creating a post, a slug is automatically generated based on the post title.

## Routes

- **Authentication**: Standard Laravel auth routes (`/login`, `/register`, `/logout`)
- **Dashboard**: `/home` (requires authentication)
- **Posts**:
    - Create post: `/posts/create`
    - Edit post: `/posts/{post}/edit`
    - View post: `/posts/{post}`
- **Comments**:
    - Add comment: `/posts/{post}/comment`
    - Delete comment: `/comments/{comment}` (only for comment owner)

## Post and Comment Relationships

Posts and comments are related as follows:
- A post has many comments.
- A comment belongs to a user.
- The `PostController` and `CommentController` manage the respective CRUD operations.