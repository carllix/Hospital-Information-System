<div align="center"> 
    <h2>Tugas Besar EB4007 Sistem Informasi Kesehatan</h2> 
    <h2>Ganesha Hospital Information System</h2>
    <p><em>Integrated Web-Based Hospital Management Information System with IoMT Support
</em></p> 
    <p>
        <img src="https://img.shields.io/badge/Status-Production-green?style=flat-square" alt="Status"/>
        <img src="https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel"/>
        <img src="https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP"/>
        <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"/>
        <img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black" alt="JavaScript"/>
        <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=flat-square&logo=postgresql&logoColor=white" alt="PostgreSQL"/>
    </p> 
    <img src="doc/logo.png" alt="Ganesha Hospital Information System" width=300 style="border-radius: 10%;"/>
</div>

## Description

**Ganesha Hospital Information System** is an integrated web-based hospital management information system developed to improve operational efficiency, accuracy of medical data management, and quality of healthcare services to patients. This system is built using the **Laravel** framework with centralized data management and _real-time_ access for all stakeholders, from patients to system administrators.

### Key Features

-   **Master Data Management** - Management of doctors, staff, practice schedules, medicines, and medical services
-   **Patient Visit Registration** - Online registration system with automatic queue numbers
-   **Medical Examination Recording** - Electronic medical records based on ICD-10
-   **Prescription Management** - E-prescribing for digital communication between doctors and pharmacy
-   **Laboratory Services** - Management of examination requests and laboratory results
-   **Integrated Payment System** - Transparent billing and payment management
-   **IoMT Integration** - Continuous health monitoring through wearable devices (heart rate & oxygen saturation)
-   **Role-Based Access Control** - Data security with access rights based on user roles

### System Users

The system is designed to support 7 user roles with different functions:

1. **Administrator** - Manage master data, system configuration, and ensure data consistency
2. **Patient** - Register visits, monitor queues, access medical history and billing
3. **Doctor** - View queues, record examinations, establish diagnoses, and prescribe medications
4. **Registration Staff** - Handle patient registration and queue management
5. **Pharmacy Staff** - Manage and dispense medications based on doctor's prescriptions
6. **Laboratory Staff** - Process examination requests and record results
7. **Cashier Staff** - Manage payment processes and issue payment receipts

## Screenshots

### 1. Administrator

**Dashboard**
![Admin Dashboard](doc/Admin-Dashboard.png)

**Doctor Management**
![Doctor Management](doc/Admin-Manajemen_Dokter.png)

**Staff Management**
![Staff Management](doc/Admin-Manajemen_Staf.png)

**Practice Schedule Management**
![Practice Schedule Management](doc/Admin-Manajemen_Jadwal_Praktik.png)

**Medicine Management**
![Medicine Management](doc/Admin-Manajemen_Obat.png)

**Service Management**
![Service Management](doc/Admin-Manajemen_Layanan.png)

**User Management**
![User Management](doc/Admin-Manajemen_Pengguna.png)

### 2. Patient

**Dashboard**
![Patient Dashboard](doc/Pasien-Dashboard.png)

**Online Visit Registration**
![Online Visit Registration](doc/Pasien-Daftar_Kunjungan_Online.png)

**Visit Schedule**
![Visit Schedule](doc/Pasien-Jadwal_Kunjungan.png)

**Medical Records**
![Medical Records](doc/Pasien-Rekam_Medis.png)

**Bills and Payments**
![Bills and Payments](doc/Pasien-Tagihan_dan_Pembayaran.png)

**Health Monitoring (IoMT)**
![Health Monitoring](doc/Pasien-Health_Monitoring.png)

**Doctor Schedule**
![Doctor Schedule](doc/Pasien-Jadwal_Dokter.png)

**Patient Profile**
![Patient Profile](doc/Pasien-Profile_Pasien.png)

### 3. Doctor

**Dashboard**
![Doctor Dashboard](doc/Dokter-Dashboard.png)

**Patient Queue**
![Patient Queue](doc/Dokter-Antrian_Pasien.png)

**Medical Examination Form**
![Medical Examination Form](doc/dokter-form_pemeriksaan.png)

**Examination History**
![Examination History](doc/Dokter-Riwayat_Pemeriksaan.png)

**Doctor Profile**
![Doctor Profile](doc/Dokter-Profile_Dokter.png)

### 4. Registration Staff

**Dashboard**
![Registration Staff Dashboard](doc/Staf_Pendaftaran-Dashboard.png)

**Patient Data**
![Patient Data](doc/Staf_Pendaftaran-Data_Pasien.png)

**Queue List**
![Queue List](doc/Staf_Pendaftaran-Daftar_Antrian.png)

**Doctor Schedule**
![Doctor Schedule](doc/Staf_Pendaftaran-Jadwal_Dokter.png)

**Visit Registration**
![Visit Registration](doc/Staf_Pendaftaran-Pendaftaran_Kunjungan.png)

**New Patient Registration**
![New Patient Registration](doc/Staf_Pendaftaran-Pendaftaran_Pasien_Baru.png)

**Registration History**
![Registration History](doc/Staf_Pendaftaran-Riwayat_Pendaftaran.png)

### 5. Pharmacy Staff

**Dashboard**
![Pharmacy Dashboard](doc/Apoteker-Dashboard.png)

**Prescription List**
![Prescription List](doc/Apoteker-Daftar_Resep.png)

**Prescription Processing**
![Prescription Processing](doc/Apoteker-Pemrosesan_Resep.png)

**Medicine Stock Management**
![Medicine Stock Management](doc/Apoteker-Manajemen_Stok_Obat.png)


### 6. Laboratory Staff

**Dashboard**
![Laboratory Dashboard](doc/Lab-Dashboard.png)

**Request List**
![Request List](doc/lab-daftar_permintaan.png)

**Examination Detail**
![Examination Detail](doc/Lab-Detail_Pemeriksaan.png)

**Lab Result Form**
![Lab Result Form](doc/Lab-Form_Hasil_Pemeriksaan_Lab.png)

**Laboratory Report**
![Laboratory Report](doc/lab-laporan.png)

### 7. Cashier Staff

**Dashboard**
![Cashier Dashboard](doc/kasir-dashboard.png)

**Bill Creation**
![Bill Creation](doc/kasir-pembuatan_tagihan.png)

**Bill Payment**
![Bill Payment](doc/Kasir-Pembayaran_Tagihan.png)

**Transaction History**
![Transaction History](doc/kasir-riwayat_transaksi.png)

**Financial Report**
![Financial Report](doc/kasir-laporan_keuangan.png)

**Transaction Invoice**
![Transaction Invoice](doc/Kasir-Invoice_Transaksi.png)

## Contributors

<table>
  <tr>
    <td align="center">
      <a href="https://github.com/WwzFwz">
        <img src="https://avatars.githubusercontent.com/WwzFwz" width="100" style="border-radius: 50%;" /><br />
        <span><b>Dzaky Aurelia Fawwaz</b></span><br/>
        <p>13523065 </p>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/carllix">
        <img src="https://avatars.githubusercontent.com/carllix" width="100" style="border-radius: 50%;" /><br />
        <span><b>Carlo Angkisan</b></span><br/>
        <p>13523091</p>
      </a>
    </td>
    <td align="center">
      <a href="https://github.com/Ferdin-Arsenic">
        <img src="https://avatars.githubusercontent.com/Ferdin-Arsenic" width="100" style="border-radius: 50%;" /><br />
        <span><b>Ferdin Arsenarendra P</b></span><br/>
        <p>13523117</p>
      </a>
    </td>
  </tr>
</table>
