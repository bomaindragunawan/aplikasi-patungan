# Setup & Testing Guide - Aplikasi Patungan

Panduan lengkap untuk setup dan testing Aplikasi Patungan.

## 📋 Prerequisites

- PHP 8.1 atau lebih baru
- Composer
- Git
- Postman atau tools testing API lainnya (optional)
- Terminal/Command Line

## 🚀 Installation Steps

### 1. Clone Repository
```bash
cd /workspaces/aplikasi-patungan
```

### 2. Install Dependencies
```bash
composer install
```

Proses ini akan menginstall semua PHP packages yang diperlukan. Tunggu hingga selesai.

### 3. Setup Environment File
```bash
cp .env.example .env
```

Jika file .env sudah ada, pastikan konfigurasi database:
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

Ini akan membuat unique key untuk enkripsi data aplikasi.

### 5. Create Database File
```bash
touch database/database.sqlite
```

Atau jika sudah ada, skip step ini.

### 6. Run Migrations
```bash
php artisan migrate
```

Output yang diharapkan:
```
   INFO  Running migrations.  

  0001_01_01_000000_create_users_table .......................... 29.05ms DONE
  0001_01_01_000001_create_cache_table .......................... 17.29ms DONE
  0001_01_01_000002_create_jobs_table ........................... 21.09ms DONE
  2026_05_03_082701_create_groups_table .......................... 7.74ms DONE
  2026_05_03_082710_create_members_table ......................... 5.27ms DONE
  2026_05_03_082710_create_transactions_table .................... 4.95ms DONE
  2026_05_03_082710_create_expense_splits_table .................. 5.64ms DONE
  2026_05_03_082711_create_settlements_table ..................... 5.76ms DONE
```

### 7. Seed Database dengan Sample Data
```bash
php artisan db:seed
```

Output yang diharapkan:
```
   INFO  Seeding database.  

  Database\Seeders\DatabaseSeeder .............................. RUNNING
  Database\Seeders\PatunganSeeder ............................... RUNNING
  Database\Seeders\PatunganSeeder ........................... 58 ms DONE
```

### 8. Start Development Server
```bash
php artisan serve
```

Server akan berjalan di: **http://localhost:8000**

Output:
```
   INFO  Server running on [http://127.0.0.1:8000].

  Press Ctrl+C to quit
```

## ✅ Verification

### Check Database
Untuk verify bahwa database sudah terbuat dengan benar:

```bash
# List tables
sqlite3 database/database.sqlite ".tables"
```

Expected output:
```
cache_locks    expense_splits  jobs           members        settlements
caches         failed_jobs     job_batches    migrations     transactions
groups         job_queues
```

### Check Sample Data
```bash
sqlite3 database/database.sqlite "SELECT * FROM groups;"
sqlite3 database/database.sqlite "SELECT * FROM members;"
sqlite3 database/database.sqlite "SELECT * FROM transactions;"
```

## 🧪 Testing API

### Using cURL (Command Line)

**Test 1: List All Groups**
```bash
curl -X GET "http://localhost:8000/api/groups" \
  -H "Accept: application/json"
```

Expected response: JSON array dengan sample groups

**Test 2: Get Group Summary**
```bash
curl -X GET "http://localhost:8000/api/groups/1/summary" \
  -H "Accept: application/json"
```

Expected response: Summary dengan balances setiap member

**Test 3: Create New Group**
```bash
curl -X POST "http://localhost:8000/api/groups" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Group",
    "description": "Ini adalah test group",
    "currency": "IDR"
  }'
```

Expected response: Group object dengan ID baru (201 Created)

**Test 4: Add Member**
```bash
curl -X POST "http://localhost:8000/api/groups/1/members" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Member",
    "email": "test@email.com",
    "phone": "08123456789"
  }'
```

**Test 5: Create Transaction**
```bash
curl -X POST "http://localhost:8000/api/groups/1/transactions" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "payer_id": 1,
    "description": "Bayar makan bersama",
    "amount": 300000,
    "category": "Makanan"
  }'
```

### Using Postman

1. **Open Postman**
2. **Create New Collection**: `Aplikasi Patungan`
3. **Create Environment Variable**:
   - Variable: `base_url`
   - Value: `http://localhost:8000/api`

4. **Create Requests**:

   **a) Get Groups**
   - Method: GET
   - URL: `{{base_url}}/groups`
   - Click Send

   **b) Create Group**
   - Method: POST
   - URL: `{{base_url}}/groups`
   - Body (raw JSON):
     ```json
     {
       "name": "Test Patungan",
       "description": "Test dari Postman",
       "currency": "IDR"
     }
     ```
   - Click Send

   **c) Get Group Summary**
   - Method: GET
   - URL: `{{base_url}}/groups/1/summary`
   - Click Send

5. **Save Request** untuk reuse nanti

## 📊 Sample Data Overview

Database sudah di-seed dengan data berikut:

### Group 1: "Patungan Liburan Bersama"
- **Members**: 4 orang (Budi, Sri, Ahmad, Putri)
- **Transactions**: 
  - Budi bayar tiket pesawat: Rp 4.000.000 (split merata)
  - Sri bayar hotel: Rp 3.000.000 (split merata)
- **Total**: Rp 7.000.000
- **Balances**:
  - Budi: +Rp 2.250.000 (harus terima)
  - Sri: +Rp 1.250.000 (harus terima)
  - Ahmad: -Rp 1.750.000 (harus bayar)
  - Putri: -Rp 1.750.000 (harus bayar)

### Group 2: "Patungan Arisan Bulanan"
- **Members**: 2 orang (Ibu Siti, Ibu Erna)
- **No transactions** (ready for testing)

## 🔧 Common Commands

### Restart Database (Clear & Reseed)
```bash
php artisan migrate:refresh --seed
```

### View Database
```bash
# Using sqlite3
sqlite3 database/database.sqlite

# In SQLite shell
sqlite> SELECT * FROM groups;
sqlite> .quit
```

### Artisan Commands Reference
```bash
# Create new model
php artisan make:model ModelName

# Create migration
php artisan make:migration create_table_name

# Create controller
php artisan make:controller ControllerName

# Create seeder
php artisan make:seeder SeederName

# List all routes
php artisan route:list

# Clear cache
php artisan cache:clear

# View configuration
php artisan config:show
```

## 📝 File Structure

```
aplikasi-patungan/
├── app/
│   ├── Models/
│   │   ├── Group.php
│   │   ├── Member.php
│   │   ├── Transaction.php
│   │   ├── ExpenseSplit.php
│   │   └── Settlement.php
│   └── Http/
│       └── Controllers/
│           ├── GroupController.php
│           ├── MemberController.php
│           ├── TransactionController.php
│           └── SettlementController.php
├── database/
│   ├── migrations/
│   │   ├── *_create_groups_table.php
│   │   ├── *_create_members_table.php
│   │   ├── *_create_transactions_table.php
│   │   ├── *_create_expense_splits_table.php
│   │   └── *_create_settlements_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── PatunganSeeder.php
│   └── database.sqlite
├── routes/
│   ├── api.php (API Routes)
│   └── web.php
├── .env (Environment)
├── README.md
├── DOCUMENTATION.md
└── SETUP_GUIDE.md
```

## 🐛 Troubleshooting

### Issue 1: "Could not find driver" or Database Error
**Solution:**
```bash
# Make sure database file exists
touch database/database.sqlite

# Run migrations again
php artisan migrate
```

### Issue 2: Port 8000 Already in Use
**Solution:**
```bash
# Use different port
php artisan serve --port=8001

# Or kill process using port 8000
lsof -i :8000  # To find process
kill -9 <PID>  # To kill
```

### Issue 3: Composer Install Error
**Solution:**
```bash
# Clear composer cache
composer clear-cache

# Update composer
composer self-update

# Try install again
composer install
```

### Issue 4: Migration Failed
**Solution:**
```bash
# Rollback migrations
php artisan migrate:reset

# Delete database and retry
rm database/database.sqlite
touch database/database.sqlite

# Run fresh migrations
php artisan migrate
php artisan db:seed
```

### Issue 5: API Returns 500 Error
**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

**Common causes:**
- Database not connected
- Invalid migration
- Model relationship issue
- Type casting problem

## 📚 Next Steps

1. **Explore API**: Test semua endpoint menggunakan Postman atau cURL
2. **Create Frontend**: Buat UI menggunakan React, Vue, atau framework lainnya
3. **Add Authentication**: Implement user authentication dengan Sanctum
4. **Add Validation**: Tambah lebih banyak validation rules
5. **Add Tests**: Buat unit tests untuk business logic
6. **Add Notifications**: Implement email/SMS notifications
7. **Deploy**: Deploy ke production server

## 📖 Resources

- **Laravel Docs**: https://laravel.com/docs/11.x
- **Eloquent ORM**: https://laravel.com/docs/11.x/eloquent
- **API Resources**: https://laravel.com/docs/11.x/eloquent-resources
- **Migrations**: https://laravel.com/docs/11.x/migrations
- **Testing**: https://laravel.com/docs/11.x/testing

## 🎓 Learning Path

### Beginner
1. Understand Models & Migrations
2. Learn Routes & Controllers
3. Practice CRUD operations
4. Study Database Relationships

### Intermediate
1. Implement Business Logic
2. Add Validation & Error Handling
3. Create Custom Events/Listeners
4. Build Advanced Queries

### Advanced
1. Performance Optimization
2. Caching Strategy
3. Queue & Jobs
4. Real-time Features

---

**Happy Testing! 🚀**

Untuk pertanyaan atau issue, silakan check:
- README.md untuk overview
- DOCUMENTATION.md untuk API details
- Laravel docs untuk framework-specific questions
