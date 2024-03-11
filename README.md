# News Portal App

## Overview

This is a news portal app that allows users to view top headlines from the News API and search the news database for specific articles. 
Users can also leave comments on articles and save their favorite articles to view later. 
Additionally, there is an admin account that can access the admin back office to view all user information and activities.

## Features

- View top headlines from the News API
- Search the news database for specific articles
- Leave comments on articles
- Save articles to favorites
- Admin account with access to the admin back office

## Technologies Used

- Laravel (PHP framework)
- pgsql (database)
- Bootstrap (CSS framework)
- News API (external API)

## Installation

1. Clone the repository
2. Run `composer install` to install the necessary dependencies
3. Create a new database
4. Copy the `.env.example` file to `.env` and update the database configuration variables
5. Run `php artisan key:generate` to generate a new application key
6. Run `php artisan migrate` to migrate the database schema
7. Run `php artisan db:seed` to seed the admin account (email: admin@admin.com  password:Admin666!)
8. Obtain a News API key from: https://newsapi.org/ and update the `config/global.php` file with the key

## Usage

1. Run `php artisan serve` to start the application
2. Run `npm run dev` to run the dev script defined in the project’s package.json file
3. Navigate to `http://localhost:8000` in your web browser
4. View top headlines or search for articles using the search bar
5. Leave comments on articles
6. Click the heart icon to add an article to your favorites list
7. Log in as the admin user to access the admin back office

## Contributing

If you'd like to contribute to this project, please fork the repository and make your changes. When you're ready to submit a pull request, please make sure to explain your changes and include any necessary documentation.

## Credits

This app was created by Matej Zagar.
