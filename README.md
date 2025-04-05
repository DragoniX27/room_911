# Room\_911 Project

This project is built to complete the **"room\_911" workshop**, implementing all required features.

It was developed using **Laravel Jetstream with Inertia.js**, and to make it work properly, follow these steps after setting up your database:

```bash
composer install

npm install && npm run dev
```

## 📅 Importing Users (Excel)

To test the bulk user import functionality, an Excel file is included:\
``

> ⚠️ **Note:** You can modify the data in the file, but **do not change the headers**, as the system relies on them during import.

## 🔧 Tech Stack

### Backend

- Laravel
- Jetstream
- Sanctum
- Spatie Laravel Permission
- Maatwebsite Excel
- DomPDF
- Inertia.js (Laravel adapter)
- Ziggy (Laravel routes in JS)

### Frontend

- Vue 3
- Inertia.js
- TailwindCSS
- Vite
- Axios
- vue-loading-overlay
- vue3-toastify

