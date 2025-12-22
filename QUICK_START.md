# Quick Start Guide - Laravel Letter Numbering System

## 🚀 Getting Started

### Start the Application
```bash
php artisan serve
```
Visit: `http://localhost:8000`

## 👥 Test Credentials

| Email | Password | Role | Permissions |
|-------|----------|------|-------------|
| admin@test.com | password | Admin | Full access |
| pimpinan@test.com | password | Pemimpin | Approve/Reject only |
| operator@test.com | password | Operator | Create/Edit letters |

## 📋 Common Tasks

### Create a Letter (Operator/Admin)
1. Login → Navigate to **Surat** menu
2. Click **"Buat Surat Baru"**
3. Select letter type from dropdown
4. Fill in date, destination (tujuan), and subject (perihal)
5. Click **Simpan** → Letter number auto-generated!

### Approve/Reject Letter (Pemimpin/Admin)
1. Go to **Surat** list
2. Find pending letter (yellow badge)
3. Click **Approve** or **Reject** button
4. Status updates immediately

### Print Letter (Any Role)
1. Only **approved** letters (green badge) can be printed
2. Click **Print** button
3. Opens print-friendly view
4. Use browser print (Ctrl+P)

### Manage Letter Types (Admin Only)
1. Navigate to **Master Tipe Surat**
2. Click **"Tambah Tipe Surat"**
3. Enter letter type name and format
4. Use placeholders: `{nomor}`, `{romawi}`, `{tahun}`

## 📝 Letter Number Format Examples

| Format | Date | Generated Number |
|--------|------|------------------|
| `500.12/DKI/` | Any | `1500.12/DKI/` |
| `500.1.2/DKI/UND/{romawi}/{tahun}/` | Dec 2025 | `1500.1.2/DKI/UND/XII/2025/` |
| `/KPTS/{romawi}/{tahun}` | Mar 2025 | `1/KPTS/III/2025` |
| `000.1.2.3/SPT/` | Any | `3000.1.2.3/SPT/` (starts from 2) |

## 🎨 Status Badges

- 🟡 **Pending** - Awaiting approval
- 🟢 **Approved** - Ready to print
- 🔴 **Rejected** - Declined

## 🔐 Role Permissions Matrix

| Feature | Admin | Pemimpin | Operator |
|---------|-------|----------|----------|
| View Dashboard | ✓ | ✓ | ✓ |
| Create Letter | ✓ | ✗ | ✓ |
| Edit Letter | ✓ | ✗ | ✓ |
| Approve Letter | ✓ | ✓ | ✗ |
| Reject Letter | ✓ | ✓ | ✗ |
| Print Approved | ✓ | ✓ | ✓ |
| Manage Master Data | ✓ | ✗ | ✗ |

## 🛠️ Development Commands

```bash
# Run migrations
php artisan migrate:fresh --seed

# Build assets (production)
npm run build

# Watch assets (development)
npm run dev

# Start server
php artisan serve
```

## 📁 Key Files

- **Routes**: `routes/web.php`
- **Controllers**: `app/Http/Controllers/`
- **Models**: `app/Models/`
- **Views**: `resources/views/`
- **Letter Service**: `app/Services/LetterNumberingService.php`
- **Migrations**: `database/migrations/`
- **Seeders**: `database/seeders/`

## ✅ System Features

✓ Auto letter numbering with Roman numerals  
✓ Role-based access control  
✓ Approval workflow (Pending → Approved/Rejected)  
✓ Print-friendly letter format  
✓ Master data management  
✓ Bootstrap 5 responsive UI  
✓ Server-side validation  
✓ Database transactions  
✓ Flash messages for feedback  

## 🎯 Next Steps

1. **Customize** letter formats in Master Tipe Surat
2. **Test** the workflow with different roles
3. **Add** more letter types as needed
4. **Configure** database in `.env` for production
5. **Deploy** to your server

---

**Need Help?** Check the [walkthrough.md](file:///C:/Users/Ideapad%20Gaming/.gemini/antigravity/brain/0e110750-522f-47cf-87d9-efe07ba7f623/walkthrough.md) for detailed documentation.
