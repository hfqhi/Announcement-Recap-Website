# 📢 CPE-3B Class Announcement & Recap Portal

**🌐 Live Site:** [cpe-announce.me](https://cpe-announce.me)

A robust, mobile-responsive web application designed to centralize college class schedules, assignment deadlines, and general announcements. Built with a clean PHP/MySQL architecture, it features a student-facing public portal and a secure admin dashboard equipped with advanced audit logging and seamless file management.

## 🎯 Purpose & Vision

The primary goal of this system is to eliminate the chaos of scattered class updates across group chats, emails, and separate messaging threads. It serves as a single, organized "source of truth" for the CPE-3B batch. By centralizing information, it ensures students always know exactly what tasks are due, when they are due, and what was discussed in class, while giving class administrators a powerful, trackable tool to manage that flow of information safely.

---

## ✨ Core Features

### 🎓 Student Portal (Public View)
*   **Dual-Mode Interface:** Users can seamlessly toggle between a highly readable **Grid Cards** view (perfect for scanning details) and a **Desktop-style Grid Calendar** view (ideal for visualizing a month's workload at a glance).
*   **Smart Attachment Renderer:** The system intelligently detects file types attached to announcements. Image files (JPG, PNG) are rendered natively inline within the announcement card, while documents (PDF, DOCX) are presented as sleek, mobile-friendly download buttons.
*   **Comprehensive Class Metadata:** General information cards display detailed class context, including the assigned professor and specific class schedule, utilizing clean UI icons for quick scanning.
*   **Smart "Days Left" Engine:** The backend calculates the exact time difference between the server's Manila timezone and the assignment deadline, outputting dynamic color-coded countdown badges (🟢 Green for 4+ days, 🟡 Yellow for 2-3 days, 🔴 Red for urgent, and ❌ Strikethrough for overdue).
*   **Lazy Auto-Archive Engine:** A lightweight background routine silently sweeps the database on every page load, automatically migrating expired tasks to the archive without requiring manual cron jobs.
*   **Intelligent Chronological Sorting:** Announcements are parsed via Regular Expressions to extract the actual class start time (e.g., pulling "10:00 AM" from the string "M 10 AM - 1 PM"), ensuring tasks on the same day are perfectly ordered by class schedule.
*   **Mobile-Optimized Experience:** The UI relies on advanced CSS media queries, flexbox layouts, and responsive table wrappers. The 7-day calendar forces a desktop-like aspect ratio but enables smooth, native horizontal swiping to prevent layout breakage on small screens.

### 🛡️ Admin Dashboard (Protected)
*   **Robust File Management:** A secure file upload system allows admins to attach documents and images to announcements. It features automatic filename sanitization to prevent conflicts, one-click file overwriting, and a dedicated soft-UI toggle for safely deleting attachments from the server.
*   **Advanced Multi-Table Search:** The dynamic search bar utilizes SQL `LEFT JOIN` operations to scan across announcement titles, content, subject codes, and subject names simultaneously for highly accurate filtering.
*   **Comprehensive Data Management:** Full CRUD capabilities for Class Subjects and Announcements, enhanced with dynamic dropdown filtering powered safely by PDO positional parameters.
*   **Mutable Subjects & Schedule Tracking:** Administrators can edit existing subject codes and attach specific class time schedules that instantly reflect on the public UI.
*   **Dynamic Global Configurations:** UI features like the 10 custom Color Themes are globally mapped via a centralized `config.php` file, instantly updating dropdowns and badges across the entire system.
*   **Admin User Management:** A secure, authenticated registration portal allows existing administrators to generate and deploy new accounts for fellow class representatives.
*   **Advanced Audit Logging:** A granular tracking system built for absolute accountability. Every single database modification is tracked, with the view intelligently defaulting to **Today's Actions** for quick daily reviews.
*   **JSON Diff Viewer:** When an admin edits an announcement, the system captures a JSON snapshot of the `old_value` and the `new_value`, allowing admins to click "View" and see exactly which specific fields were altered.
*   **Cascade Deletion Protection:** Deleting a Subject automatically triggers a backend routine that pre-logs the hard-deletion of all its associated child announcements before safely executing the cascade.

### 🔒 Security Implementations
*   **Secure File Handling:** Uploaded files undergo rigorous path sanitization (regex filtering, `basename()`) to prevent directory traversal attacks, and the absolute paths are mapped dynamically using `dirname(__DIR__)` to ensure cross-platform compatibility between Windows development and Linux production environments.
*   **CSRF Protection:** Custom, cryptographically secure token verification is enforced on all state-changing form submissions.
*   **SQL Injection Prevention:** 100% Prepared Statements (PHP Data Objects) utilizing strict positional parameters (`?`) are utilized across the entire system, alongside a strict structural whitelist for dynamic database table targeting.
*   **XSS Prevention:** Strict HTML entity encoding (`ENT_QUOTES`) is applied to all user-rendered outputs.
*   **Authentication Guards:** Session-based authentication guards protect all admin routes, with the active admin's username dynamically displayed in the global navbar.

---

## ⚙️ How The System Works

The application operates on an **MVC-Lite** architecture, creating a strict separation between database communication, backend logic, and frontend presentation.

1.  **Data Ingestion:** Administrators log into the secured dashboard to publish announcements, upload files, or update subjects. The backend ensures all data is strictly bound to the `Asia/Manila` timezone at both the PHP and MySQL levels to guarantee deadline accuracy regardless of server location.
2.  **State Tracking (The Audit Engine):** Before any `UPDATE` or `DELETE` query is executed, the backend fetches the current state of the target row. It executes the change, fetches the new state, and saves both as JSON strings in the `tbl_audit_log`, creating a permanent, immutable history of system events.
3.  **Data Presentation:** When a student visits the public portal, the backend retrieves all active announcements.
4.  **The Parsing & Rendering Engine:** The PHP backend filters out null dates, dynamically resolves relative file paths for attachments, calculates the "Days Left" metrics, and uses the Regex parser to sort the items chronologically. It then generates the CSS Grid layout for the calendar, mapping the filtered arrays onto the correct days of the month, before finally serving the compiled HTML/Bootstrap UI to the user's browser.

---

## 🛠️ Technology Stack

*   **Frontend Presentation:** HTML5, CSS3, Vanilla JavaScript.
*   **Frontend Frameworks:** Bootstrap 5.3 (Layout & Components), Bootstrap Icons.
*   **Backend Processing:** PHP 8.x (Procedural structure with Object-Oriented PDO for database interactions).
*   **Database Management:** MySQL 8.0 / MariaDB (Relational structure with cascading foreign keys).
*   **Server Environment:** Designed for Linux VPS (LAMP Stack, `www-data` optimized) or standard Apache web servers with `.htaccess` routing capabilities.