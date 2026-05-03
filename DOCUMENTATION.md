# Dokumentasi API Aplikasi Patungan

Panduan lengkap untuk menggunakan API Aplikasi Patungan dengan contoh-contoh praktis.

## Base URL
```
http://localhost:8000/api
```

## Authentication
API ini tidak memerlukan authentication khusus (untuk development). Untuk production, tambahkan middleware authentication sesuai kebutuhan.

---

## 📌 1. GROUPS API

### 1.1 List Semua Grup

**Endpoint:**
```
GET /api/groups
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "name": "Patungan Liburan Bersama",
    "description": "Arisan untuk liburan akhir tahun ke Bali",
    "currency": "IDR",
    "created_at": "2026-05-03T08:30:00.000000Z",
    "updated_at": "2026-05-03T08:30:00.000000Z"
  },
  {
    "id": 2,
    "name": "Patungan Arisan Bulanan",
    "description": "Arisan rutin bulanan keluarga",
    "currency": "IDR",
    "created_at": "2026-05-03T08:35:00.000000Z",
    "updated_at": "2026-05-03T08:35:00.000000Z"
  }
]
```

### 1.2 Buat Grup Baru

**Endpoint:**
```
POST /api/groups
```

**Request Body:**
```json
{
  "name": "Patungan Tahunan 2026",
  "description": "Tabungan untuk keperluan tahunan",
  "currency": "IDR"
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/groups" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Patungan Tahunan 2026",
    "description": "Tabungan untuk keperluan tahunan",
    "currency": "IDR"
  }'
```

**Response (201 Created):**
```json
{
  "id": 3,
  "name": "Patungan Tahunan 2026",
  "description": "Tabungan untuk keperluan tahunan",
  "currency": "IDR",
  "created_at": "2026-05-03T09:00:00.000000Z",
  "updated_at": "2026-05-03T09:00:00.000000Z"
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `description`: optional, string
- `currency`: optional, string, max 10 characters (default: IDR)

### 1.3 Detail Grup

**Endpoint:**
```
GET /api/groups/{id}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups/1" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
  "id": 1,
  "name": "Patungan Liburan Bersama",
  "description": "Arisan untuk liburan akhir tahun ke Bali",
  "currency": "IDR",
  "created_at": "2026-05-03T08:30:00.000000Z",
  "updated_at": "2026-05-03T08:30:00.000000Z",
  "members": [
    {
      "id": 1,
      "group_id": 1,
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "phone": "08123456789",
      "created_at": "2026-05-03T08:31:00.000000Z",
      "updated_at": "2026-05-03T08:31:00.000000Z"
    }
  ],
  "transactions": [],
  "settlements": []
}
```

### 1.4 Update Grup

**Endpoint:**
```
PUT /api/groups/{id}
```

**Request Body:**
```json
{
  "name": "Liburan Bali Terbaru 2026",
  "description": "Update deskripsi grup"
}
```

**cURL Example:**
```bash
curl -X PUT "http://localhost:8000/api/groups/1" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Liburan Bali Terbaru 2026",
    "description": "Update deskripsi grup"
  }'
```

### 1.5 Delete Grup

**Endpoint:**
```
DELETE /api/groups/{id}
```

**cURL Example:**
```bash
curl -X DELETE "http://localhost:8000/api/groups/1" \
  -H "Accept: application/json"
```

**Response (204 No Content):**
```
(empty response)
```

### 1.6 Summary Grup

Mendapatkan ringkasan grup dengan total transaksi dan balance setiap anggota.

**Endpoint:**
```
GET /api/groups/{id}/summary
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups/1/summary" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
  "group": {
    "id": 1,
    "name": "Patungan Liburan Bersama",
    "description": "Arisan untuk liburan akhir tahun ke Bali",
    "currency": "IDR",
    "created_at": "2026-05-03T08:30:00.000000Z",
    "updated_at": "2026-05-03T08:30:00.000000Z"
  },
  "total_transactions": 7000000,
  "members_count": 4,
  "balances": [
    {
      "member_id": 1,
      "member_name": "Budi Santoso",
      "paid": 4000000,
      "spent": 1750000,
      "balance": 2250000
    },
    {
      "member_id": 2,
      "member_name": "Sri Wijaya",
      "paid": 3000000,
      "spent": 1750000,
      "balance": 1250000
    },
    {
      "member_id": 3,
      "member_name": "Ahmad Rizki",
      "paid": 0,
      "spent": 1750000,
      "balance": -1750000
    },
    {
      "member_id": 4,
      "member_name": "Putri Indah",
      "paid": 0,
      "spent": 1750000,
      "balance": -1750000
    }
  ]
}
```

### 1.7 Balance Detail Grup

**Endpoint:**
```
GET /api/groups/{id}/balance
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups/1/balance" \
  -H "Accept: application/json"
```

---

## 👥 2. MEMBERS API

### 2.1 List Anggota dalam Grup

**Endpoint:**
```
GET /api/groups/{group_id}/members
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups/1/members" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "group_id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "phone": "08123456789",
    "created_at": "2026-05-03T08:31:00.000000Z",
    "updated_at": "2026-05-03T08:31:00.000000Z"
  },
  {
    "id": 2,
    "group_id": 1,
    "name": "Sri Wijaya",
    "email": "sri@example.com",
    "phone": "08234567890",
    "created_at": "2026-05-03T08:31:30.000000Z",
    "updated_at": "2026-05-03T08:31:30.000000Z"
  }
]
```

### 2.2 Tambah Anggota ke Grup

**Endpoint:**
```
POST /api/groups/{group_id}/members
```

**Request Body:**
```json
{
  "name": "Andi Pratama",
  "email": "andi@example.com",
  "phone": "08512345678"
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/groups/1/members" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Andi Pratama",
    "email": "andi@example.com",
    "phone": "08512345678"
  }'
```

**Response (201 Created):**
```json
{
  "id": 5,
  "group_id": 1,
  "name": "Andi Pratama",
  "email": "andi@example.com",
  "phone": "08512345678",
  "created_at": "2026-05-03T09:15:00.000000Z",
  "updated_at": "2026-05-03T09:15:00.000000Z"
}
```

**Validation Rules:**
- `name`: required, string, max 255 characters
- `email`: optional, email format
- `phone`: optional, string

### 2.3 Detail Anggota

**Endpoint:**
```
GET /api/members/{id}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/members/1" \
  -H "Accept: application/json"
```

### 2.4 Update Anggota

**Endpoint:**
```
PUT /api/members/{id}
```

**Request Body:**
```json
{
  "name": "Budi Santoso Updated",
  "email": "budi.new@example.com"
}
```

### 2.5 Delete Anggota

**Endpoint:**
```
DELETE /api/members/{id}
```

---

## 💳 3. TRANSACTIONS API

### 3.1 List Transaksi dalam Grup

**Endpoint:**
```
GET /api/groups/{group_id}/transactions
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups/1/transactions" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "group_id": 1,
    "payer_id": 1,
    "description": "Bayar tiket pesawat",
    "amount": "4000000.00",
    "category": "Transportasi",
    "transaction_date": "2026-05-03T12:00:00.000000Z",
    "created_at": "2026-05-03T08:45:00.000000Z",
    "updated_at": "2026-05-03T08:45:00.000000Z",
    "payer": {
      "id": 1,
      "group_id": 1,
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "phone": "08123456789",
      "created_at": "2026-05-03T08:31:00.000000Z",
      "updated_at": "2026-05-03T08:31:00.000000Z"
    },
    "expense_splits": [
      {
        "id": 1,
        "transaction_id": 1,
        "member_id": 1,
        "amount": "1000000.00",
        "status": "pending",
        "created_at": "2026-05-03T08:45:30.000000Z",
        "updated_at": "2026-05-03T08:45:30.000000Z"
      }
    ]
  }
]
```

### 3.2 Buat Transaksi (Auto-split)

Jika tidak menyertakan field `splits`, biaya akan dibagi merata ke semua anggota.

**Endpoint:**
```
POST /api/groups/{group_id}/transactions
```

**Request Body:**
```json
{
  "payer_id": 1,
  "description": "Bayar makan bersama",
  "amount": 400000,
  "category": "Makanan"
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/groups/1/transactions" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "payer_id": 1,
    "description": "Bayar makan bersama",
    "amount": 400000,
    "category": "Makanan"
  }'
```

**Response (201 Created):**
```json
{
  "id": 3,
  "group_id": 1,
  "payer_id": 1,
  "description": "Bayar makan bersama",
  "amount": "400000.00",
  "category": "Makanan",
  "transaction_date": "2026-05-03T12:00:00.000000Z",
  "created_at": "2026-05-03T09:30:00.000000Z",
  "updated_at": "2026-05-03T09:30:00.000000Z",
  "payer": { /* payer object */ },
  "expense_splits": [
    {
      "id": 9,
      "transaction_id": 3,
      "member_id": 1,
      "amount": "100000.00",
      "status": "pending",
      "created_at": "2026-05-03T09:30:15.000000Z",
      "updated_at": "2026-05-03T09:30:15.000000Z"
    },
    {
      "id": 10,
      "transaction_id": 3,
      "member_id": 2,
      "amount": "100000.00",
      "status": "pending",
      "created_at": "2026-05-03T09:30:15.000000Z",
      "updated_at": "2026-05-03T09:30:15.000000Z"
    },
    {
      "id": 11,
      "transaction_id": 3,
      "member_id": 3,
      "amount": "100000.00",
      "status": "pending",
      "created_at": "2026-05-03T09:30:15.000000Z",
      "updated_at": "2026-05-03T09:30:15.000000Z"
    },
    {
      "id": 12,
      "transaction_id": 3,
      "member_id": 4,
      "amount": "100000.00",
      "status": "pending",
      "created_at": "2026-05-03T09:30:15.000000Z",
      "updated_at": "2026-05-03T09:30:15.000000Z"
    }
  ]
}
```

### 3.3 Buat Transaksi (Custom Split)

**Request Body:**
```json
{
  "payer_id": 1,
  "description": "Makan malam bersama",
  "amount": 600000,
  "category": "Makanan",
  "splits": [
    {
      "member_id": 1,
      "amount": 150000
    },
    {
      "member_id": 2,
      "amount": 200000
    },
    {
      "member_id": 3,
      "amount": 250000
    }
  ]
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/groups/1/transactions" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "payer_id": 1,
    "description": "Makan malam bersama",
    "amount": 600000,
    "category": "Makanan",
    "splits": [
      {
        "member_id": 1,
        "amount": 150000
      },
      {
        "member_id": 2,
        "amount": 200000
      },
      {
        "member_id": 3,
        "amount": 250000
      }
    ]
  }'
```

**Validation Rules:**
- `payer_id`: required, must exist in members table
- `description`: required, string
- `amount`: required, numeric, min 0.01
- `category`: optional, string
- `transaction_date`: optional, date format
- `splits`: optional, array of splits
  - `member_id`: required, must exist in members table
  - `amount`: required, numeric, min 0

### 3.4 Update Expense Splits

**Endpoint:**
```
POST /api/transactions/{id}/split
```

**Request Body:**
```json
{
  "splits": [
    {
      "member_id": 1,
      "amount": 250000
    },
    {
      "member_id": 2,
      "amount": 250000
    },
    {
      "member_id": 3,
      "amount": 100000
    }
  ]
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/transactions/1/split" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "splits": [
      {
        "member_id": 1,
        "amount": 250000
      },
      {
        "member_id": 2,
        "amount": 250000
      },
      {
        "member_id": 3,
        "amount": 100000
      }
    ]
  }'
```

### 3.5 Detail Transaksi

**Endpoint:**
```
GET /api/transactions/{id}
```

### 3.6 Update Transaksi

**Endpoint:**
```
PUT /api/transactions/{id}
```

**Request Body:**
```json
{
  "description": "Bayar makan bersama - updated",
  "amount": 450000
}
```

### 3.7 Delete Transaksi

**Endpoint:**
```
DELETE /api/transactions/{id}
```

---

## 🤝 4. SETTLEMENTS API

### 4.1 List Settlement dalam Grup

**Endpoint:**
```
GET /api/groups/{group_id}/settlements
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/groups/1/settlements" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
[
  {
    "id": 1,
    "group_id": 1,
    "from_member_id": 3,
    "to_member_id": 1,
    "amount": "1750000.00",
    "status": "pending",
    "settled_date": null,
    "created_at": "2026-05-03T10:00:00.000000Z",
    "updated_at": "2026-05-03T10:00:00.000000Z",
    "from_member": {
      "id": 3,
      "group_id": 1,
      "name": "Ahmad Rizki",
      "email": "ahmad@example.com",
      "phone": "08345678901",
      "created_at": "2026-05-03T08:32:00.000000Z",
      "updated_at": "2026-05-03T08:32:00.000000Z"
    },
    "to_member": {
      "id": 1,
      "group_id": 1,
      "name": "Budi Santoso",
      "email": "budi@example.com",
      "phone": "08123456789",
      "created_at": "2026-05-03T08:31:00.000000Z",
      "updated_at": "2026-05-03T08:31:00.000000Z"
    }
  }
]
```

### 4.2 Buat Settlement

Catat pembayaran hutang/settlement antar anggota.

**Endpoint:**
```
POST /api/groups/{group_id}/settlements
```

**Request Body:**
```json
{
  "from_member_id": 3,
  "to_member_id": 1,
  "amount": 1750000
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/groups/1/settlements" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "from_member_id": 3,
    "to_member_id": 1,
    "amount": 1750000
  }'
```

**Response (201 Created):**
```json
{
  "id": 1,
  "group_id": 1,
  "from_member_id": 3,
  "to_member_id": 1,
  "amount": "1750000.00",
  "status": "pending",
  "settled_date": null,
  "created_at": "2026-05-03T10:00:00.000000Z",
  "updated_at": "2026-05-03T10:00:00.000000Z",
  "from_member": { /* from_member object */ },
  "to_member": { /* to_member object */ }
}
```

### 4.3 Mark Settlement as Paid

Menandai settlement sebagai sudah dibayar.

**Endpoint:**
```
POST /api/settlements/{id}/mark-paid
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/settlements/1/mark-paid" \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
  "id": 1,
  "group_id": 1,
  "from_member_id": 3,
  "to_member_id": 1,
  "amount": "1750000.00",
  "status": "completed",
  "settled_date": "2026-05-03T10:30:00.000000Z",
  "created_at": "2026-05-03T10:00:00.000000Z",
  "updated_at": "2026-05-03T10:30:00.000000Z"
}
```

### 4.4 Detail Settlement

**Endpoint:**
```
GET /api/settlements/{id}
```

### 4.5 Update Settlement

**Endpoint:**
```
PUT /api/settlements/{id}
```

**Request Body:**
```json
{
  "amount": 1800000
}
```

### 4.6 Delete Settlement

**Endpoint:**
```
DELETE /api/settlements/{id}
```

---

## 🚨 Error Responses

### 400 Bad Request
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "payer_id": [
      "The payer id field is required."
    ],
    "amount": [
      "The amount must be at least 0.01."
    ]
  }
}
```

### 404 Not Found
```json
{
  "message": "No query results found for model [App\\Models\\Group] 1"
}
```

### 500 Server Error
```json
{
  "message": "Internal server error",
  "error": "Error details here"
}
```

---

## 📊 Contoh Workflow Lengkap

### Scenario: Patungan Liburan dengan 3 Orang

**Step 1: Buat Grup**
```bash
curl -X POST "http://localhost:8000/api/groups" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Liburan Ke Bandung",
    "description": "Liburan keluarga ke Bandung",
    "currency": "IDR"
  }'
```

**Step 2: Tambah Anggota (repeat 3x)**
```bash
# Anggota 1
curl -X POST "http://localhost:8000/api/groups/1/members" \
  -H "Content-Type: application/json" \
  -d '{"name": "Ibu Siti", "email": "ibu.siti@email.com"}'

# Anggota 2
curl -X POST "http://localhost:8000/api/groups/1/members" \
  -H "Content-Type: application/json" \
  -d '{"name": "Ibu Erna", "email": "ibu.erna@email.com"}'

# Anggota 3
curl -X POST "http://localhost:8000/api/groups/1/members" \
  -H "Content-Type: application/json" \
  -d '{"name": "Pak Ahmad", "email": "pak.ahmad@email.com"}'
```

**Step 3: Catat Transaksi**
```bash
# Ibu Siti bayar tiket (dibagi 3)
curl -X POST "http://localhost:8000/api/groups/1/transactions" \
  -H "Content-Type: application/json" \
  -d '{
    "payer_id": 1,
    "description": "Tiket pesawat Bandung",
    "amount": 3000000,
    "category": "Transportasi"
  }'

# Ibu Erna bayar hotel
curl -X POST "http://localhost:8000/api/groups/1/transactions" \
  -H "Content-Type: application/json" \
  -d '{
    "payer_id": 2,
    "description": "Hotel Bandung 3 hari",
    "amount": 1500000,
    "category": "Akomodasi"
  }'
```

**Step 4: Lihat Summary**
```bash
curl -X GET "http://localhost:8000/api/groups/1/summary" \
  -H "Accept: application/json"
```

**Step 5: Catat Pembayaran**
```bash
# Pak Ahmad bayar ke Ibu Siti
curl -X POST "http://localhost:8000/api/groups/1/settlements" \
  -H "Content-Type: application/json" \
  -d '{
    "from_member_id": 3,
    "to_member_id": 1,
    "amount": 1500000
  }'

# Mark as paid
curl -X POST "http://localhost:8000/api/settlements/1/mark-paid" \
  -H "Accept: application/json"
```

---

Selamat menggunakan API Aplikasi Patungan!
