Room_911 Project
This project was created to complete the "room_911" workshop, fulfilling all the required functionalities.

It is built with Laravel Jetstream, using Inertia.js and Vue 3 on the frontend.

🛠️ Requirements
PHP >= 8.1

Composer

Node.js >= 18

NPM or Yarn

MySQL or compatible database

⚙️ Installation
Clone the repository

bash
Copiar
Editar
git clone <your-repo-url>
cd room_911
Install PHP dependencies

bash
Copiar
Editar
composer install
Install JavaScript dependencies and compile assets

bash
Copiar
Editar
npm install && npm run dev
Configure the .env file
Copy the example file and update your database and other environment variables:

bash
Copiar
Editar
cp .env.example .env
php artisan key:generate
Run migrations

bash
Copiar
Editar
php artisan migrate
(Optional) Seed the database

bash
Copiar
Editar
php artisan db:seed
📥 Bulk User Import
To test the bulk user import functionality, an example file is included:
test_excel_room_911.xlsx

You can modify the file’s content as needed, but do not change the column headers, since the import logic uses them as references.

To perform the import:

bash
Copiar
Editar
php artisan import:users test_excel_room_911.xlsx
(or use the UI if the import feature is integrated there)

📄 Features
User Management (Create, Edit, Delete, Block)

Role & Department Assignment

Login Attempt Tracking

Bulk User Import with Excel (Laravel Excel + Spatie)

PDF Export (if enabled)

Jetstream UI Components with Tailwind CSS

Dynamic Form Validations

📦 Packages Used
Laravel Jetstream

Inertia.js

Vue 3

Tailwind CSS

Laravel Excel

Spatie Laravel Permission

🧩 Project Dependencies
Laravel (Composer)
This project uses the following key PHP dependencies:

laravel/framework – Laravel core framework

laravel/jetstream – Authentication scaffolding with Inertia.js and Vue 3

inertiajs/inertia-laravel – Inertia adapter for Laravel

spatie/laravel-permission – Role and permission management

maatwebsite/excel – Excel import/export support

barryvdh/laravel-dompdf – PDF generation

tightenco/ziggy – Laravel named routes in JavaScript

laravel/sanctum – API token authentication

Node.js (package.json)
Frontend dependencies include:

Vue 3 – JavaScript framework

@inertiajs/vue3 – Vue 3 support for Inertia

tailwindcss – Utility-first CSS framework

@tailwindcss/forms – Better default styles for forms

@tailwindcss/typography – Rich content formatting

vue-loading-overlay – Loading spinners

vue3-toastify – Toast notifications

axios – HTTP client

vite – Frontend bundler

laravel-vite-plugin – Laravel + Vite integration
