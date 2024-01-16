# aplikasi-perpustakaan
Untuk memenuhi tugas Mata Kuliah WEB SERVICE

## Features
- Authentication User
- List Book (CRUD)
- List Author (CRUD)
- List Category (CRUD)
- List Publisher (CRUD)
- List Member (CRUD)
- List Transaction untuk Peminjaman dan Pengmbalian Buku (CRUD)

## Tech

- [Laravel/Lumen]
- [MySQL]
- [VueJs]

## Installation

# Front End

```sh
cd front-end-perpustakaan
npm install
npm run serve
```

# Back End

```sh
cd back-end-perpustakaan
composer install
cp .env.example .env
php artisan files:link
php -S localhost:8003 -t public
```

## Api Documentantion 

Run Via Postman Collection [https://api.postman.com/collections/10585571-7a590e17-825f-4066-8c0d-39c9372ae0a0?access_key=PMAT-01HM594CFPMACVTGAWNGZVH0DC]