Tazkiyah : Aplikasi POS (Point of Sales) Berbasis Web / Open Source
===============================

Aplikasi ini adalah aplikasi POS atau aplikasi kasir yang memudahkan usaha kafe, warung kecil, minimarket, toko kelontong dalam mengelola transaksi penjualan dan order. Lebih lengkap tentang fitur di Tazkiyah POS, silakan kunjungi website kami di https://blog.muhajirin.net/2018/01/aplikasi-pos-berbasis-web-gratis-open-source.html

Kebutuhan Sistem 
----------------

Aplikasi ini menggunakan Yii2 Framework (advanced project template) dalam pengembangannya, jadi proses instalasi dan kebutuhannya mirip. 

 - PHP
 - MySQL
 - Web Server (Apache, dll)
 - Web Browser seperti Google Chrome, Firefox, dll
 - Composer
 - Git (opsional)

Proses Instalasi
----------------

Lakukan clone repositori ini pada web direktori (lebih disarankan)
```
cd /var/www/tazkiyah
git clone https://github.com/prabowomurti/tazkiyah-pos.git .
```

Langkah di atas dapat diganti dengan melakukan download secara manual melalui link berikut : https://github.com/prabowomurti/tazkiyah-pos/archive/master.zip

Lakukan instalasi dengan menggunakan composer (https://getcomposer.org). Tunggu hingga proses download package yang dibutuhkan selesai.

```
composer install
```

Initiate aplikasi dengan perintah 
```
./init
```

Pilih antara `development` atau `production` untuk aplikasi Anda.

Modifikasi file `common/config/main-local.php`, cari bagian berikut ini, dan ubah sesuai database yang Anda persiapkan.

```
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=nama_database', // ubah 'nama_database' menjadi database yang Anda siapkan
            'username' => 'mysql_username', // ubah sesuai dengan username mysql Anda
            'password' => 'k@t@$and1_s0esaHdiT3b4k', // ubah sesuai password DB
            'charset' => 'utf8',
        ],
```

Migrasi database, dengan perintah berikut.
```
./yii migrate
```

Tambahkan user pertama (Super Admin) dengan perintah berikut, dan ikuti langkah-langkahnya.
```
./yii install/add-admin
```

Hingga langkah ini, Anda tinggal mengakses http://localhost/tazkiyah/backend/web (sesuaikan dengan direktori tempat Anda melakukan instalasi). Atau menggunakan perintah berikut

```
./yii serve --docroot="backend/web"
```

Lalu akses melalui http://localhost:8080 dan login dengan account yang sudah Anda tambahkan saat menjalankan perintah `./yii install/add-admin` sebelumnya.

Pertanyaan dan Saran
--------------------

Jika Anda mengalami kesulitan, silakan [tambahkan issue](https://github.com/prabowomurti/tazkiyah-pos/issues/new) atau berikan komentar pada [artikel kami](https://blog.muhajirin.net/2018/01/aplikasi-pos-berbasis-web-gratis-open-source.html). Terima kasih.
