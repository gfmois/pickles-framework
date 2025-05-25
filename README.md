# Pickles Framework Project

Pickles is a PHP web application framework designed for rapid development, modularity, and simplicity. It provides a robust structure for building modern web applications with features such as routing, authentication, database migrations, and more.

## Features
- Modular service provider architecture
- MVC structure (Controllers, Models, Views)
- Built-in authentication (login, registration, session management)
- Database migrations
- Environment-based configuration
- CLI utilities for development and management

## Requirements
- PHP 8.3 or higher
- Composer
- MySQL (default, configurable but for now only MySQL and Postgres can be used)

## Installation
### Using composer
```powershell
   composer create-project gfmois/pickles-framework <name:optional>
```

### Using git
1. Clone the repository:
   ```powershell
   git clone <your-repo-url> pickles
   cd pickles
   ```
2. Install dependencies:
   ```powershell
   composer install
   ```
3. Copy the example environment file and configure your settings:
   ```powershell
   copy .env.example .env
   # Edit .env as needed
   ```
4. Set up your database (ensure the credentials in `.env` are correct).

## Usage
- **Start the development server:**
  ```powershell
  php -S localhost:8000 -t public
  ```
- **Run CLI commands:**
  ```powershell
  php pickles.php
  ```
  This will show available CLI commands for migrations and other utilities.

## Project Structure
- `app/` - Application code (Controllers, Models, Providers)
- `config/` - Configuration files
- `database/` - Migrations
- `public/` - Public web root
- `resources/` - Views and templates
- `routes/` - Route definitions
- `vendor/` - Composer dependencies

## License
This project is licensed under the MIT License. See [LICENSE.md](LICENSE.md) for details.

## Author
Moisés (<daw.moisesguerola@gmail.com>)
