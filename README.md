# 📢 CPE-2B Class Announcement & Recap Portal

**🌐 Live Site:** [cpe-announce.me](https://cpe-announce.me)

A robust, mobile-responsive web application designed to centralize college class schedules, assignment deadlines, and general announcements. Built with a clean PHP/MySQL architecture, it features a student-facing public portal and a secure admin dashboard equipped with advanced audit logging.

## 🎯 Purpose & Vision

The primary goal of this system is to eliminate the chaos of scattered class updates across group chats, emails, and separate messaging threads. It serves as a single, organized "source of truth" for the CPE-2B batch. By centralizing information, it ensures students always know exactly what tasks are due, when they are due, and what was discussed in class, while giving class administrators a powerful, trackable tool to manage that flow of information safely.

---

## ✨ Core Features

### 🎓 Student Portal (Public View)
* **Dual-Mode Interface:** Users can seamlessly toggle between a highly readable **Grid Cards** view (perfect for scanning details) and a **Desktop-style Grid Calendar** view (ideal for visualizing a month's workload at a glance).
* **Smart "Days Left" Engine:** The backend calculates the exact time difference between the server's Manila timezone and the assignment deadline. It dynamically outputs color-coded countdown badges:
  * 🟢 Green: 4+ Days remaining.
  * 🟡 Yellow: 2-3 Days remaining.
  * 🔴 Red: **Due Tomorrow!** or **Due Today!** (Urgent).
  * ❌ Strikethrough: Overdue/Missed.
* **Intelligent Chronological Sorting:** Announcements aren't just grouped by date; they are parsed via Regular Expressions to extract the actual class start time (e.g., pulling "10:00 AM" from the string "M 10 AM - 1 PM"). Tasks on the same day are perfectly ordered by class schedule.
* **Mobile-Optimized Experience:** The UI relies on advanced CSS media queries and flexbox layouts. Instead of breaking or squishing the 7-day calendar on mobile devices, it forces a desktop-like aspect ratio but enables smooth, native horizontal swiping.

### 🛡️ Admin Dashboard (Protected)
* **Comprehensive Data Management:** Full CRUD (Create, Read, Update, Delete) capabilities for both Class Subjects and specific Announcements, enhanced with dynamic **Search & Dropdown Filtering** powered safely by PDO positional parameters.
* **Admin User Management:** A secure, authenticated registration portal allowing existing administrators to generate and deploy new accounts for fellow class representatives.
* **Advanced Audit Logging:** A granular tracking system built for absolute accountability. Every single database modification (creates, updates, archives, restores, and hard-deletes) is tracked, with the view intelligently defaulting to **Today's Actions** for quick daily reviews. 
  * *JSON Diff Viewer:* When an admin edits an announcement, the system captures a JSON snapshot of the `old_value` and the `new_value`, allowing admins to click "View" and see exactly which specific fields were altered.
* **Cascade Deletion Protection:** To prevent silent data loss, deleting a Subject automatically triggers a backend routine that pre-logs the hard-deletion of all its associated child announcements before safely executing the cascade.

### 🔒 Security Implementations
* **CSRF Protection:** Custom, cryptographically secure token verification is enforced on all state-changing form submissions.
* **SQL Injection Prevention:** 100% Prepared Statements (PHP Data Objects) utilizing strict positional parameters (`?`) are strictly used across the entire system.
* **XSS Prevention:** Strict HTML entity encoding (`ENT_QUOTES`) is applied to all user-rendered outputs.
* **Authentication Guards:** Session-based authentication guards protect all admin routes, with the active admin's username dynamically displayed in the global navbar.

---

## ⚙️ How The System Works

The application operates on an **MVC-Lite** architecture, creating a strict separation between database communication, backend logic, and frontend presentation.

1. **Data Ingestion:** Administrators log into the secured dashboard to publish announcements or update subjects. The backend ensures all data is strictly bound to the `Asia/Manila` timezone at both the PHP and MySQL levels to guarantee deadline accuracy regardless of server location.
2. **State Tracking (The Audit Engine):** Before any `UPDATE` or `DELETE` query is executed, the backend fetches the current state of the target row. It executes the change, fetches the new state, and saves both as JSON strings in the `tbl_audit_log`, creating a permanent, immutable history of system events.
3. **Data Presentation:** When a student visits the public portal, the backend retrieves all active announcements. 
4. **The Parsing & Rendering Engine:** The PHP backend filters out null dates, calculates the "Days Left" metrics, and uses the Regex parser to sort the items chronologically. It then generates the CSS Grid layout for the calendar, mapping the filtered arrays onto the correct days of the month, before finally serving the compiled HTML/Bootstrap UI to the user's browser.

---

## 🛠️ Technology Stack

* **Frontend Presentation:** HTML5, CSS3, Vanilla JavaScript.
* **Frontend Frameworks:** Bootstrap 5.3 (Layout & Components), Bootstrap Icons.
* **Backend Processing:** PHP 8.x (Procedural structure with Object-Oriented PDO for database interactions).
* **Database Management:** MySQL 8.0 / MariaDB (Relational structure with cascading foreign keys).
* **Server Environment:** Designed for Linux VPS (LAMP Stack) or standard Apache web servers with `.htaccess` routing capabilities.
