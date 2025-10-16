# Install

```bash
git clone https://github.com/dpfeifer-dotcom/chat-app.git chat-app
cd chat-app
cp .env.example .env
```

If you want to use the mail service, fill in the following environment variables:

- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION
- MAIL_FROM_ADDRESS

```bash
composer install
vendor/bin/sail up -d
```

# Setup  

```bash
vendor/bin/sail artisan migrate
```

If you want to generate users where the first 5 are marked as friends, then register yourself and afterwards run the following command:

```bash
vendor/bin/sail artisan db:seed
```

# Documentaions

http://localhost/docs#endpoints

# Testing

```bash
vendor/bin/sail artisan test
```
