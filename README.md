# Aplikasi Patungan - Sistem Manajemen Biaya Bersama

Aplikasi Laravel untuk mengelola biaya bersama dalam grup atau keluarga (arisan digital). Fitur utama mencakup pencatatan transaksi, pembagian biaya otomatis, dan pelacakan hutang.

## 📋 Fitur Utama

- ✅ **Manajemen Grup**: Buat dan kelola grup patungan
- ✅ **Manajemen Anggota**: Tambah anggota ke dalam grup
- ✅ **Pencatatan Transaksi**: Catat setiap pengeluaran/pembayaran
- ✅ **Pembagian Biaya Otomatis**: Split biaya secara merata atau custom
- ✅ **Pelacakan Hutang**: Lihat siapa yang hutang berapa ke siapa
- ✅ **Pencatat Pembayaran Settlement**: Catat pembayaran hutang antar member
- ✅ **Analitik Kelompok**: Ringkasan total pengeluaran dan saldo per anggota

## 🚀 Quick Start

### Prerequisites
- PHP 8.1+
- Composer
- SQLite atau database lainnya

### Installation

1. **Setup Repository**
```bash
cd /workspaces/aplikasi-patungan
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Run Migrations**
```bash
php artisan migrate
```

5. **Seed Sample Data**
```bash
php artisan db:seed
```

6. **Start Server**
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

7. **Frontend Laravel**
- Frontend sudah tersedia menggunakan Blade.
- Buka `http://localhost:8000` untuk mengakses antarmuka web.

## 📚 API Documentation

Base URL: `http://localhost:8000/api`

### 1. Groups (Grup Patungan)

#### List All Groups
```
GET /api/groups
```

#### Create Group
```
POST /api/groups
Content-Type: application/json

{
  "name": "Patungan Liburan Ke Bandung",
  "description": "Liburan keluarga besar",
  "currency": "IDR"
}
```

#### Get Group Details
```
GET /api/groups/{id}
```

#### Get Group Summary
```
GET /api/groups/{id}/summary
```

#### Get Detailed Balance
```
GET /api/groups/{id}/balance
```

#### Update Group
```
PUT /api/groups/{id}
```

#### Delete Group
```
DELETE /api/groups/{id}
```

### 2. Members (Anggota Grup)

#### List Members in Group
```
GET /api/groups/{group_id}/members
```

#### Add Member to Group
```
POST /api/groups/{group_id}/members
Content-Type: application/json

{
  "name": "Andi Pratama",
  "email": "andi@example.com",
  "phone": "08123456789"
}
```

#### Get Member Details
```
GET /api/members/{id}
```

#### Update Member
```
PUT /api/members/{id}
```

#### Delete Member
```
DELETE /api/members/{id}
```

### 3. Transactions (Transaksi/Pengeluaran)

#### List Transactions in Group
```
GET /api/groups/{group_id}/transactions
```

#### Create Transaction (dengan auto-split ke semua anggota)
```
POST /api/groups/{group_id}/transactions
Content-Type: application/json

{
  "payer_id": 1,
  "description": "Bayar makan bersama",
  "amount": 400000,
  "category": "Makanan"
}
```

#### Create Transaction (dengan custom split)
```
POST /api/groups/{group_id}/transactions
Content-Type: application/json

{
  "payer_id": 1,
  "description": "Bayar makan bersama",
  "amount": 400000,
  "category": "Makanan",
  "splits": [
    {
      "member_id": 1,
      "amount": 100000
    },
    {
      "member_id": 2,
      "amount": 150000
    },
    {
      "member_id": 3,
      "amount": 150000
    }
  ]
}
```

#### Get Transaction Details
```
GET /api/transactions/{id}
```

#### Update Expense Splits
```
POST /api/transactions/{id}/split
Content-Type: application/json

{
  "splits": [
    {
      "member_id": 1,
      "amount": 200000
    },
    {
      "member_id": 2,
      "amount": 200000
    }
  ]
}
```

#### Update Transaction
```
PUT /api/transactions/{id}
```

#### Delete Transaction
```
DELETE /api/transactions/{id}
```

### 4. Settlements (Pencatat Pembayaran Hutang)

#### List Settlements in Group
```
GET /api/groups/{group_id}/settlements
```

#### Create Settlement
```
POST /api/groups/{group_id}/settlements
Content-Type: application/json

{
  "from_member_id": 2,
  "to_member_id": 1,
  "amount": 1000000
}
```

#### Mark Settlement as Paid
```
POST /api/settlements/{id}/mark-paid
```

#### Get Settlement Details
```
GET /api/settlements/{id}
```

#### Update Settlement
```
PUT /api/settlements/{id}
```

#### Delete Settlement
```
DELETE /api/settlements/{id}
```

## 💡 Use Cases

### Case 1: Patungan Liburan Keluarga
1. Buat group "Liburan Ke Bali"
2. Tambah 4 anggota keluarga
3. Budi bayar tiket pesawat Rp 4.000.000 → dibagi 4
4. Sri bayar hotel Rp 3.000.000 → dibagi 4
5. Lihat summary dan balance masing-masing
6. Catat pembayaran settlement antar member

### Case 2: Arisan Bulanan
1. Buat group "Arisan Bulanan 2026"
2. Tambah member-member arisan
3. Setiap bulan, pembayaran arisan dicatat sebagai transaction
4. Split biaya sesuai kesepakatan
5. Tracking pembayaran dan hutang

### Case 3: Spending Tracking Tim
1. Buat group untuk tim/departemen
2. Track semua spending/pengeluaran bersama
3. Analisis siapa yang paling banyak bayar
4. Lihat outstanding balance

## 🔧 Teknologi Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.4
- **Database**: SQLite (default, dapat diganti PostgreSQL, MySQL)
- **API**: RESTful JSON API

## 📝 Model Relationships

```
Group
├── Members (one-to-many)
├── Transactions (one-to-many)
└── Settlements (one-to-many)

Member
├── Group (belongs-to)
├── Transactions (one-to-many)
├── ExpenseSplits (one-to-many)
├── SettlementsFrom (one-to-many)
└── SettlementsTo (one-to-many)

Transaction
├── Group (belongs-to)
├── Payer/Member (belongs-to)
└── ExpenseSplits (one-to-many)

ExpenseSplit
├── Transaction (belongs-to)
└── Member (belongs-to)

Settlement
├── Group (belongs-to)
├── FromMember (belongs-to)
└── ToMember (belongs-to)
```

## 📞 Support & Referen

- Laravel Documentation: https://laravel.com/docs
- API routes: `/routes/api.php`
- Models: `/app/Models/`
- Controllers: `/app/Http/Controllers/`

---

**Selamat menggunakan Aplikasi Patungan! 🎉**
