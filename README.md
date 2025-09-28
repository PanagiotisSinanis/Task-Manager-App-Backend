<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Task Manager App Backend

Το backend της εφαρμογής Task Manager είναι χτισμένο με το Laravel
, ένα δημοφιλές PHP framework για την ανάπτυξη σύγχρονων web εφαρμογών. Το έργο παρέχει ένα API για τη διαχείριση εργασιών, χρηστών και ρόλων, ιδανικό για προσωπική ή επαγγελματική χρήση.

🚀 Χαρακτηριστικά

Διαχείριση Χρηστών: Δημιουργία, επεξεργασία και διαγραφή χρηστών.

Διαχείριση Εργασιών: Δημιουργία, ενημέρωση, διαγραφή και ανάκτηση εργασιών.

Ρόλοι Χρηστών: Υποστήριξη διαφορετικών ρόλων χρηστών με περιορισμένα δικαιώματα.

Ασφαλής Αυθεντικοποίηση: Χρήση JWT (JSON Web Tokens) για ασφαλή έλεγχο ταυτότητας.

Δοκιμές: Περιλαμβάνονται μονάδες δοκιμών για βασικές λειτουργίες του API.

🛠️ Τεχνολογίες

PHP 8.1+

Laravel 9.x

MySQL ή MariaDB

Composer

PHPUnit για δοκιμές

📦 Εγκατάσταση

Κλωνοποιήστε το αποθετήριο:

git clone https://github.com/PanagiotisSinanis/Task-Manager-App-Backend.git
cd Task-Manager-App-Backend


Εγκαταστήστε τις εξαρτήσεις με Composer:

composer install


Δημιουργήστε το αρχείο περιβαλλοντικών μεταβλητών:

cp .env.example .env


Ρυθμίστε τη βάση δεδομένων στο αρχείο .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=


Δημιουργήστε το σχήμα της βάσης δεδομένων:

php artisan migrate


(Προαιρετικά) Γεμίστε τη βάση δεδομένων με ψεύτικα δεδομένα:

php artisan db:seed


Ξεκινήστε τον ενσωματωμένο διακομιστή ανάπτυξης:

php artisan serve


Η εφαρμογή θα είναι διαθέσιμη στη διεύθυνση http://localhost:8000.

🧪 Δοκιμές

Για να εκτελέσετε τις μονάδες δοκιμών:

php artisan test

📄 Τεκμηρίωση API

Η τεκμηρίωση του API είναι διαθέσιμη στο αρχείο docs/API.md. Περιλαμβάνει:

Λίστα όλων των διαθέσιμων endpoints.

Παραδείγματα αιτημάτων και απαντήσεων.

Περιγραφές παραμέτρων και κωδικών κατάστασης HTTP.

📌 Σημειώσεις

Το έργο είναι ακόμα υπό ανάπτυξη. Ενδέχεται να υπάρχουν αλλαγές στο API ή στη δομή της βάσης δεδομένων.

Για οποιεσδήποτε ερωτήσεις ή προτάσεις, παρακαλούμε ανοίξτε ένα issue.
npm run dev
php artisan serve
