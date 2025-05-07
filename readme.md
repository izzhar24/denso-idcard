# ID Card System

Application Description
-----------------------

izzhar-php-framework is a simple PHP framework for building web applications. It is designed to be lightweight and easy to use, while still providing the features and functionality needed to build robust and scalable applications.

Features
--------

* **MVC Architecture**: izzhar-php-framework uses the Model-View-Controller (MVC) architecture pattern to separate the application logic into three interconnected components. This makes it easier to maintain and extend the application.
* **Routing**: The framework provides a simple routing system that allows you to map URLs to specific controllers and actions.
* **Database Abstraction**: The framework provides a simple database abstraction layer that allows you to interact with the database using a simple and intuitive API.
* **Template Engine**: The framework provides a simple template engine that allows you to render views using a simple and intuitive syntax.
* **Security**: The framework provides a simple security system that allows you to protect your application from common web attacks such as SQL injection and cross-site scripting (XSS).
* **Internationalization**: The framework provides a simple internationalization system that allows you to translate your application into multiple languages.

Requirements
------------
* **PHP**:  requires PHP version 8.0 or higher to run.
* **Composer**: The framework requires Composer to be installed in order to manage dependencies.
* **Git**: The framework requires Git to be installed in order to clone the repository and manage the codebase.

## Installation
------------
To install the framework, simply clone the repository and run the following command in your terminal:
### Clone Project
```bash
git clone https://github.com/izzhar24/denso-idcard.git 
cd denso-idcard
```

###  Install depedency 
```bash
composer install
```

### Copy file env.example dan sesuaikan isinya
```bash
cp .env.example .env
```

### Migration data
php migrate.php --refresh

### Seeder dummy data
```bash
php seed
```

### Running application
```bash
php -S localhost:9000 -t public
```

### Login admin panel
- URL : https://localhost::9000/admin
- Akun 
```bash
Admin:
    email : admin@mail.com
    password: admin
User:
    email: user@mail.com
    password: user


