Got it ✅ — here’s a **professional, GitHub-ready `README.md`** draft for your **Laravel Gym Management System** project (with sections for screenshots, installation, features, etc.).

---

# 🏋️‍♂️ Laravel Gym Management System

A complete **Gym Management System** built with **Laravel**, designed to manage memberships, trainers, workouts, POS sales, and attendance — all in one clean, responsive dashboard.

---

| Screen                      | Preview                                                               | Description                                                                       |
| :-------------------------- | :-------------------------------------------------------------------- | :-------------------------------------------------------------------------------- |
| **Welcome Page**            | ![welcome](screenshots/welcome.png)                                   | Landing page of the system before login.                                          |
| **Admin Dashboard**         | ![Admin\_Dasboard](screenshots/Admin_Dasboard.png)                    | Overview of total members, trainers, and revenue.                                 |
| **Add Member**              | ![Add\_Member](screenshots/Add_Member.png)                            | Form to register a new gym member.                                                |
| **Member List**             | ![Member\_List](screenshots/Member_List.png)                          | View, edit, or delete registered gym members.                                     |
| **Fee Plan**                | ![Fee\_Plan](screenshots/Fee_Plan.png)                                | Create and manage gym membership fee plans.                                       |
| **Fee Plan List**           | ![Fee\_Plan\_List](screenshots/Fee_Plan_List.png)                     | Shows all available membership fee plans.                                         |
| **Fee Date-Wise Report**    | ![Fee\_Date\_Wise\_Report](screenshots/Fee_Date_Wise_Report.png)      | Report for collected fees by date.                                                |
| **Collect Fee**             | ![Collect\_Fee](screenshots/Collect_Fee.png)                          | Record and manage fee payments from members.                                      |
| **Mark Attendance**         | ![Mark\_Attendance](screenshots/Mark_Attendance.png)                  | Attendance screen for daily check-ins.                                            |
| **Mark Attendance 2**       | ![Mark\_Attendance2](screenshots/Mark_Attendance2.png)                | Alternate attendance layout or confirmation view.                                 |
| **Mark Attendance Success** | ![Mark\_Attendance\_Success](screenshots/Mark_Attendance_Success.png) | Confirmation of successful attendance marking.                                    |
| **Product List**            | ![Product\_List](screenshots/Product_List.png)                        | All available products for sale in POS.                                           |
| **POS Sale Screen**         | ![POS\_Sale\_Screen](screenshots/POS_Sale_Screen.png)                 | Point-of-sale screen to select products, set quantity, and auto-calculate totals. |
| **Sales List**              | ![Sales\_List](screenshots/Sales_List.png)                            | View all completed sales transactions.                                            |
| **Sale Print**              | ![Sale\_Print](screenshots/Sale_Print.png)                            | Auto-generated printable receipt after a sale.                                    |



## 🚀 Features

* 👤 **Member Management** — Add, edit, renew, or deactivate gym members.
* 🧾 **POS Sales Module** — Sell products, auto-calculate totals, and print receipts.
* 💪 **Trainer Management** — Assign trainers to members, manage schedules.
* 📅 **Attendance Tracking** — Log daily check-ins and activity records.
* 💰 **Subscription Plans** — Define and manage membership plans with durations.
* 📊 **Reports & Analytics** — Track revenue, attendance, and membership trends.
* 🔐 **Role-Based Access Control** — Admin, Staff, and Trainer panels.
* 🎨 **Responsive UI** — Built using Bootstrap + Blade templates for a modern look.

---

## ⚙️ Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/kamranmeyo/laravel-gym-management.git
cd laravel-gym-management
```

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Environment Setup

Copy the example `.env` file and configure your database:

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
DB_DATABASE=gym_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 5. Serve the Application

```bash
php artisan serve
```

Now visit 👉 **[http://localhost:8000](http://localhost:8000)**

---


## 🖨️ POS Sale & Receipt Printing

The POS system includes:

* Product search and quantity input
* Auto total calculation
* Submit and generate printable receipt

🧩 Example:

```bash
POST /sales
```

Generates a sale record and prints a formatted receipt using browser print dialog.

---

## 🧠 Tech Stack

* **Framework:** Laravel 11
* **Frontend:** Blade, Bootstrap, jQuery
* **Database:** MySQL
* **Auth:** Laravel Breeze / Jetstream
* **Printing:** JS Print / POS Receipt Layout

---

## 🛠️ Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
├── Migrations/
resources/
├── views/
│   ├── dashboard.blade.php
│   ├── pos/
│   ├── members/
│   └── trainers/
public/
└── screenshots/
```

---

## 💡 Future Enhancements

* 📱 Progressive Web App (PWA)
* 💳 Online Payment Integration
* 📬 Automated Email Reminders for Renewals
* 📈 Advanced Analytics Dashboard

---

## 👨‍💻 Author

**Muhammad Kamran Saeed**
📧 [[kamranmeyo786@gmail.com](mailto:kamranmeyo786@gmail.com)]
🌐 [github.com/kamranmeyo](https://github.com/yourusername)
