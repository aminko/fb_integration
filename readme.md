## Facebook integration

Lets users login to Laravel based application using Facebook Oauth 
 
## Features
You can:
- login via Facebook Oauth
- post link on Facebook


## Requirements

- PHP 7.0+
- MySQL
- mod_rewrite activated
- Composer

## Installation 

Start by setting up **FB_APP_ID**, **FB_APP_SECRET**, **FB_APP_CALLBACK**  enviroment variables in **.env** file.

**FB_APP_ID** and **FB_APP_SECRET** will be generated when app is created on https://developers.facebook.com

**FB_APP_CALLBACK** should contain URL for Oauth callback (i.e. https://example.com/fb/auth/callback). 
**Note**: Callback URL must also match a "Valid OAuth Redirect URL" in the "Facebook Login" tab under your app, otherwise Facebook might reject it.


Then run composer to install dependencies:
```bash
composer install
```
And migrate database tables:
```bash
php artisan migrate
```

This application also requires that you have at least one active user in database Users table. You can add user through **/register** endpoint.

