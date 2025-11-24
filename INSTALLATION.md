# Zamzam CRM - Installation & Quick Start Guide

## System Overview

Complete automotive CRM system with:
- **5 User Roles**: Super Admin, Sales Manager, Sales Agent, HR, Customer
- **Vehicle Management**: Full CRUD with photo/video uploads
- **Customer Management**: Lead tracking, assignment system
- **Invoice System**: Payment tracking with multiple payment methods
- **HR Module**: Employees, departments, shifts, attendance, payroll
- **Public API**: Vehicle listings for website (NO price data)

---

## Quick Start (5 Minutes)

### 1. Link Storage for File Uploads
```bash
php artisan storage:link
```

### 2. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

This will create:
- ✅ All database tables (17 tables)
- ✅ Roles & Permissions (5 roles, 49 permissions)
- ✅ Demo users for all roles
- ✅ 8 sample vehicles
- ✅ 2 demo customers
- ✅ Departments, shifts, employees
- ✅ 1 sample invoice

### 3. Start Development Server
```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## Demo Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | admin@zamzam.com | password |
| **Sales Manager** | manager@zamzam.com | password |
| **Sales Agent 1** | agent1@zamzam.com | password |
| **Sales Agent 2** | agent2@zamzam.com | password |
| **HR Manager** | hr@zamzam.com | password |
| **Customer 1** | customer1@example.com | password |
| **Customer 2** | customer2@example.com | password |

---

## What You Can Test Immediately

### As Super Admin (`admin@zamzam.com`)
- ✅ View complete system overview
- ✅ See all vehicles, customers, invoices
- ✅ Access all modules
- ✅ View activity logs
- ✅ Manage users and roles

### As Sales Manager (`manager@zamzam.com`)
- ✅ Add/edit vehicles
- ✅ Create customers
- ✅ Assign customers to agents
- ✅ View sales reports
- ✅ Manage vehicle status (Available → Reserved → Sold)

### As Sales Agent (`agent1@zamzam.com` or `agent2@zamzam.com`)
- ✅ View assigned customers
- ✅ Create invoices
- ✅ Add payments to invoices
- ✅ View all vehicles
- ✅ Track own sales performance

### As HR (`hr@zamzam.com`)
- ✅ Manage employees
- ✅ Manage departments & shifts
- ✅ View attendance (when implemented)
- ✅ Create announcements

### As Customer (`customer1@example.com`)
- ✅ View invoices
- ✅ See payment history
- ✅ View assigned vehicles
- ✅ Chat with agent (when implemented)

---

## Public API Endpoints (For Website)

### Get All Vehicles
```
GET /api/vehicles
```

**Query Parameters:**
- `make` - Filter by make
- `model` - Filter by model
- `year` - Filter by year
- `year_from` - Min year
- `year_to` - Max year
- `fuel_type` - Filter by fuel type
- `transmission` - Filter by transmission
- `condition` - New or Used
- `per_page` - Items per page (default: 12, max: 50)
- `sort_by` - Sort field (created_at, year, make, model, mileage)
- `sort_order` - asc or desc

**Example:**
```bash
curl http://localhost:8000/api/vehicles?make=Toyota&condition=Used
```

### Get Single Vehicle
```
GET /api/vehicles/{id}
```

### Get Filter Options
```
GET /api/vehicles/filters
```

**Note:** API returns NO price data - prices negotiated via WhatsApp.

---

## File Upload Configuration

### Vehicle Photos
- **Max:** 10 photos per vehicle
- **Size:** 5MB per photo
- **Format:** JPEG, PNG, JPG
- **Storage:** `storage/app/public/vehicles/photos`

### Vehicle Videos
- **Max:** 1 video per vehicle
- **Size:** 50MB max
- **Format:** MP4, MOV, AVI
- **Storage:** `storage/app/public/vehicles/videos`

### Documents
- **Max:** 10MB per file
- **Format:** PDF, Images
- **Storage:** `storage/app/public/documents`

---

## Environment Configuration

### Required .env Settings

```env
APP_NAME="Zamzam CRM"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zamzam_crm
DB_USERNAME=root
DB_PASSWORD=

# For Email Notifications (Optional - configure when ready)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@zamzam.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## System Architecture

### Database Tables (17)
1. users
2. customers
3. vehicles
4. vehicle_photos
5. departments
6. employees
7. shifts
8. attendances
9. leaves
10. payrolls
11. announcements
12. invoices
13. invoice_payments
14. messages
15. customer_notes
16. documents
17. activity_logs
18. notifications

### Roles & Permissions
- **Super Admin**: Full system access
- **Sales Manager**: Vehicles, customers, reports
- **Sales Agent**: Assigned customers, invoices
- **HR**: Employee management, payroll
- **Customer**: Portal access only

---

## Next Steps (After Testing)

### 1. Create Views (Pending)
Routes are set up but views need to be created for:
- Dashboards (5 different ones)
- Vehicle CRUD forms
- Customer management screens
- Invoice forms
- HR module screens

### 2. Additional Controllers (Pending)
- MessageController (chat)
- EmployeeController (HR)
- LeaveController
- PayrollController
- AnnouncementController
- ReportController

### 3. Email Notifications
Configure SMTP and create email templates for:
- Customer credentials
- Invoice generation
- Payment reminders
- Leave approvals

---

## Testing Checklist

- [ ] Super Admin can log in and see dashboard
- [ ] Sales Manager can create vehicles
- [ ] Sales Manager can create customers
- [ ] Sales Agent can see assigned customers only
- [ ] Sales Agent can create invoices
- [ ] Customer can log in and see their invoices
- [ ] Public API returns vehicles WITHOUT prices
- [ ] File uploads work (photos, videos)
- [ ] Activity logs track actions
- [ ] Role-based redirects work

---

## Troubleshooting

### Storage Link Not Working
```bash
php artisan storage:link
```

### Permissions Errors
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Database Errors
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## Development Status

✅ **Completed (70%)**
- Database structure
- Models & relationships
- Authentication system
- Role-based access control
- Vehicle management controller
- Customer management controller
- Invoice controller
- Public API
- Demo data seeder

⏳ **In Progress (30%)**
- Views & UI (Bootstrap templates)
- HR module controllers
- Message/Chat system
- Reports & analytics
- Email notifications
- PDF generation

---

## Support & Documentation

- **Progress Tracker**: See `PROJECT_PROGRESS.md`
- **Requirements**: See `requirement.md`
- **Controllers**: Located in `app/Http/Controllers`
- **Models**: Located in `app/Models`
- **Migrations**: Located in `database/migrations`

---

**Built with Laravel 12 + Bootstrap 5 + MySQL**
