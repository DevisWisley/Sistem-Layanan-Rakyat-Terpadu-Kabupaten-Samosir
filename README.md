# 🏛️ SILARA

**Sistem Layanan Rakyat Terpadu Kabupaten Samosir (SILARA)** is a web-based application designed to **simplify the digital process for the public to request and monitor public services**. 

This system supports **multi-role login (Admin, Resident, Service)** and features an **interactive dashboard, resident data, service data, and online submissions.** It includes **Bootstrap, TailwindCSS, SweetAlert2, dan Chart.js**.

---

## ✨ Features

- 🔐 **Multi-Role Authentication**
  - Login and Register for **Admin**, **Residents**, and **Service Officers**
  - Page protection with PHP sessions
  - Automatic redirection based on login role

- 🧾 **Landing Page (index.php)**
  - Hero section with dynamic background (AOS)
  - Login/register navigation
  - Public service information and statistics (Chart.js)
  - Informative footer with official contacts

- 📊 **Interactive Dashboard (Admin / Services / Residents)**
  - Layout components: TopBar, Sidebar, Footer
  - Submission statistics & number of services with Chart.js
  - Action notifications using SweetAlert2
  - Interactive tables (DataTables)

- 👥 **CRUD Population Data**
  - NIK, Full Name, Email, Date of Birth, Telephone Number, Village, Sub-district
  - Input validation & upload of supporting files (Dropzone.js)
  - Search and filter with DataTables

- 🧰 **CRUD Service Data**
  - Service ID, Name, Category, Description, and Document Requirements
  - Add, edit, and delete features with SweetAlert2 confirmation

- 📨 **CRUD Submission Data**
  - Submission ID, NIK, Service ID, Date, Status, Description
  - Upload supporting files via Dropzone.js
  - Status notification to residents

- 🧑‍💼 **CRUD Admin Data**
  - Admin ID, Name, Username, Position, Password, Role
  - Admin and service officer account management

- 📈 **Statistics & Reports**
  - Graph of the number of submissions per service (Chart.js)
  - Resident data summary per sub-district

- 📄 **.htaccess Protection**
  - URL rewrite friendly
  - Restrict access to certain folders (upload, config)

---

## ⚙️ Technology Used

| Icon | Teknologi | Deskripsi |
|------|-----------|-----------|
| <img src="https://www.bram.us/wordpress/wp-content/uploads/2016/08/68747470733a2f2f7333322e706f7374696d672e6f72672f6b7476743539686f6c2f616f735f6865616465722e706e67-300x186.png" width="30"/> | **AOS (Animate On Scroll)** | Animation effects when elements appear on the screen (landing page and dashboard). |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/Bootstrap.svg" width="30"/> | **Bootstrap** | CSS framework for responsive layouts and ready-to-use interface components. |
| <img src="https://scicoding.com/content/images/2021/09/chartjs-logo-1.svg" width="30"/> | **Chart.js** | Data visualization in the form of bar, pie, and line graphs. |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/CSS.svg" width="30"/> | **CSS** | To beautify the appearance |
| <img src="https://www.opencodez.com/wp-content/uploads/2017/02/datatable.png" width="30"/> | **DataTables** | Provides interactive tables (search, sort, pagination). |
| <img src="https://iconape.com/wp-content/png_logo_vector/dropzone.png" width="30"/> | **Dropzone.js** | Library upload files with drag and drop preview. |
| <img src="https://tse3.mm.bing.net/th/id/OIP.IIym5Ox6guJgGP18tta4CQHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" width="30"/> | **Font Awesome** | Icons for navigation UI, buttons, and graphical elements. |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/Git.svg" width="30"/> | **Git** | Version control |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/Github-Dark.svg" width="30"/> | **GitHub** | Source code repository |
| <img src="https://cdn.pnggallery.com/wp-content/uploads/google-fonts-logo-02.png" width="30"/> | **Google Fonts** | Professional typography for interface display. |
| <img src="https://tse4.mm.bing.net/th/id/OIP.8C4FVrGE8BFPTHyktRMQKQHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" width="30"/> | **.htaccess** | URL rewrite & folder security. |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/JavaScript.svg" width="30"/> | **JavaScript** | increase user interaction. |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/JQuery.svg" width="30"/> | **jQuery** | DOM manipulation and event handling (AJAX, modal, form). |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/Markdown-Dark.svg" width="30"/> | **Markdown** | System documentation format and user manual. |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/MySQL-Dark.svg" width="30"/> | **MySQL** | Relational database for storing population, service, application and admin data. |
| <img src="https://github.com/RashmiDulashani/Skill-Icons/raw/main/icons/PHP-Dark.svg" width="30"/> | **PHP Native** | The primary language for server processing, authentication, and MySQL database connections without a framework. |
| <img src="https://sweetalert2.github.io/images/favicon.png" width="30"/> | **SweetAlert2** | Modern popups for user action confirmation and notification. |
| <img src="https://github.com/tandpfun/skill-icons/raw/main/icons/TailwindCSS-Dark.svg" width="30"/> | **Tailwind CSS** | Utility-first CSS framework for flexible appearance customization. |

---

## 📂 Struktur Folder
```
└── 📦 silara
    └── 📂.history
    └── 📂.vscode
    └── 📂auth
        ├── 📜 login.php
        ├── 📜 logout.php
        ├── 📜 register.php
    └── 📂config
        ├── 📜 db.php
    └── 📂dashboard
        ├── 📜 admin.php
        ├── 📜 buat_pengajuan.php
        ├── 📜 index.php
        ├── 📜 layanan.php
        ├── 📜 penduduk.php
        ├── 📜 pengajuan.php
        ├── 📜 settings.php
        ├── 📜 status.php
    └── 📂database
        ├── 📜 code.sql
   └── 📂includes
        ├── 📜 footer.php
        ├── 📜 header.php
        ├── 📜 sidebar.php
        ├── 📜 topbar.php
   └── 📂pages
        ├── 📜 about.php
   └── 📂uploads
        ├── 📜 profile.png
    ├── 📜 .htaccess
    ├── 📜 CODE_OF_CONDUCT
    ├── 📜 index.php
    ├── 📜 LICENSE
    ├── 📜 README.md
    └── 📜 SECURITY
```

---

## 🗃️ Struktur Database (MySQL)

### 1. Table `penduduk`
| Column | Data Type | Information |
|--------|------------|------------|
| nik | VARCHAR(20) | Primary Key |
| nama_lengkap | VARCHAR(100) | Full name of resident |
| email | VARCHAR(100) | Active email |
| tgl_lahir | DATE | Date of birth |
| no_telp | VARCHAR(20) | Phone number |
| desa | VARCHAR(100) | Village name |
| kecamatan | VARCHAR(100) | Subdistrict name |

### 2. Table `layanan`
| Column | Data Type | Information |
|--------|------------|------------|
| id_layanan | INT AUTO_INCREMENT | Primary Key |
| nama_layanan | VARCHAR(100) | Service name |
| kategori | VARCHAR(50) | Service categories |
| deskripsi | TEXT | Service descriptionlayanan |
| syarat_dokumen | TEXT | File/document requirements |

### 3. Table `pengajuan`
| Column | Data Type | Information |
|--------|------------|------------|
| id_pengajuan | INT AUTO_INCREMENT | Primary Key |
| nik | VARCHAR(20) | Relation to population table |
| id_layanan | INT | Relationship to service table |
| tgl_pengajuan | DATE | Submission date |
| status | ENUM('Diproses','Selesai','Ditolak') | Service process status |
| keterangan | TEXT | Additional notes |
| file_pendukung | VARCHAR(255) | Supporting documents |

### 4. Table `admin`
| Column | Data Type | Information |
|--------|------------|------------|
| id_admin | INT AUTO_INCREMENT | Primary Key |
| nama_admin | VARCHAR(100) | Name of officer/admin |
| username | VARCHAR(50) | Login username |
| jabatan | VARCHAR(50) | Title/position |
| password | VARCHAR(255) | Encrypted password |
| role | ENUM('Admin','Layanan') | Access rights |

---

## ▶️ How to Run
1. 📥 **Clone repositori:**
   ```bash
   git clone https://github.com/Sistem-Layanan-Rakyat-Terpadu-Kabupaten-Samosir.git
   ```

   - Or download ZIP:
     - Click the `Code` button > `Download ZIP`
     - Extract the ZIP file to a folder of your choice
2. 🖥️ Siapkan XAMPP
   - ⚡ Enable Apache and MySQL via XAMPP Control Panel
   - 📂 Move the silara folder to the directory:
    ```bash
   C:\xampp\htdocs\silara

   ```

3. 🗃️ **Import Database**  
    Open `phpMyAdmin` then **import** the `db_silara.sql` file
4. ⚙️ **Database Configuration**  
   Edit the `db.php` file and adjust it to your MySQL configuration:

   ```php
   $host = "localhost";
   $user = "root";
   $password = "";
   $db = "db_silara";
5. 🌐 **Run Application**
    Open a browser and access: `http://localhost/silara

---

## 🖼️ Media & File Handling
- 📁 File Upload (**Dropzone.js**) – Profile photo upload feature.
- 🖼️ Image Preview (**JavaScript**) – Displays a preview of the uploaded image before saving it to the server.
- 🔍 Validasi ukuran & format otomatis

## 🔧 Development & Testing

| Tools | Description |
|-------|-----------|
| 🖥️ XAMPP | Local server environment to run PHP + MySQL locally.|
| 🗂️ phpMyAdmin | Web interface for managing MySQL databases.|
| 🐙 Git | Version control system used to track changes, manage project versions, and collaborate across development workflows. |
| 🌐 GitHub | Online repository hosting service for storing source code, managing issues, documentation, collaboration, and CI/CD workflows. |
| 🧪 Google Chrome DevTools | For element inspection, CSS/JS debugging, and responsive testing.|
| 📝 Visual Studio Code | The main code editor used for project development.|

---

## 🙋‍♂️ Developer

This project was developed by **Devis Wisley**, a web developer with a passion for PHP-based web application development, modern UI/UX design, and the integration of front-end technologies like Bootstrap and Tailwind CSS. If you have any questions, would like to discuss, or are interested in collaborating on similar projects, please contact us through one of the following platforms:

| Contact Information | Detail |
|------------------|--------|
| 📛 **Nama**         | Devis Wisley |
| 📧 **Email**        | [deviswisley27@gmail.com](mailto:deviswisley27@gmail.com) – Please send questions, collaborations, or project feedback. |
| 🌐 **Portfolio**    | [codingindo.vercel.app](https://codingindo.vercel.app/) – See other projects that have been worked on. |
| 🐙 **GitHub**       | [github.com/deviswisley](https://www.github.com/deviswisley) – Source code repository and open source contributions. |
| 📘 **Facebook**     | [facebook.com/devis.wisley](https://www.facebook.com/devis.wisley/) – Connect and have a relaxed discussion. |
| 📸 **Instagram**    | [instagram.com/deviswisley](https://www.instagram.com/deviswisley/) – Activities and design work shared visually. |
| 🔗 **LinkedIn**     | [linkedin.com/in/deviswisley](https://www.linkedin.com/in/deviswisley/) – Professional network and work experience. |
| 📱 **WhatsApp**     | [Chat via WhatsApp](https://api.whatsapp.com/send?phone=6282274107967) – Connect instantly for fast communication. |

---

Please get in touch if you have suggestions, request additional features, would like to provide support, or are interested in similar projects.
