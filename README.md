# laravel-saas-demo

## Getting started

Clone this repo and run commands in the order below:

```
composer install
yarn install
cp .env.example .env # And edit the values
php artisan key:generate
```

Fill the "STRIPE_KEY", "STRIPE_SECRET", "STRIPE_WEBHOOK_SECRET" environment variables at the and of .env file.

Then start Docker containers using Sail:

```
sail up -d
```

Run the migrations

```
sail artisan migrate --seed
```

### Front-end assets

Open another terminal tab and run the command below to compile front-end assets:

```bash
yarn run dev
```

### Stripe Webhooks

Install the [Stripe CLI](https://stripe.com/docs/stripe-cli) to use webhook forwarding.

Open another terminal tab and run the command below:

```
stripe listen --forward-to localhost/stripe/webhook
```

Now you can access the project at http://localhost in your browser.
