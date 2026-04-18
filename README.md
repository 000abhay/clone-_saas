# CodeVocado Clone

Simple Laravel login clone based on the provided SaaS design.

## Features

- Dark login UI inspired by the reference screenshot
- Demo account section removed from the page
- Session-based authentication
- Seeded users for multiple roles
- Protected dashboard after login

## Seeded Users

All seeded users share this password:

`Password123!`

- `sarah.admin@codevocado.com` - Super Admin
- `mike.manager@codevocado.com` - Admin
- `james.sales@codevocado.com` - Sales Manager
- `emma.exec@codevocado.com` - Sales Executive
- `david.support@codevocado.com` - Support Agent

## Run

```bash
php artisan migrate:fresh --seed
php artisan serve
```
