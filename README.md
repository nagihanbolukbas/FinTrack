# 💰 FinTrack

FinTrack, kullanıcıların kişisel finanslarını kolay, güvenli ve verimli bir şekilde yönetebilmeleri amacıyla geliştirilen web tabanlı bir Kişisel Finans Yönetim Sistemidir.

Bu proje, İstanbul Gelişim Üniversitesi Web Tasarımı ve Kodlama Programı kapsamında gerçekleştirilen zorunlu yaz stajı sürecinde **CRS Soft** bünyesinde geliştirilmiştir.

---

## 🚀 Proje Amacı

FinTrack ile kullanıcıların;

- Gelir ve giderlerini kayıt altına alması,
- Finansal durumunu anlık olarak takip etmesi,
- Tasarruf hedefleri oluşturması ve ilerleme durumunu görüntülemesi,
- Grafikler aracılığıyla finansal verilerini analiz etmesi,
- Finansal hareketlerini PDF raporu olarak dışa aktarması,
- Güncel döviz kurları üzerinden finansal değerlerini farklı para birimlerinde görüntüleyebilmesi

amaçlanmıştır.

---

## ✨ Özellikler

- 🔐 Kullanıcı kayıt ve güvenli giriş sistemi
- 👤 Güvenli oturum (Session) yönetimi
- 📊 Dashboard (Genel finans özeti)
- 💰 Gelir yönetimi (Ekle / Düzenle / Sil)
- 💸 Gider yönetimi (Ekle / Düzenle / Sil)
- 🏷️ Kategori yönetimi
- 🎯 Tasarruf hedefleri oluşturma ve takip etme
- 💵 Gelirlerden doğrudan tasarruf hedefine para aktarma
- 👤 Profil yönetimi
- 📈 Chart.js ile gelir-gider analiz grafikleri
- 📄 PDF finansal raporlama
- 💱 Güncel döviz kuru entegrasyonu
- 📅 Aylık finansal özet
- 📋 Son işlemler görüntüleme

---

## 🛠️ Kullanılan Teknolojiler

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- PDO
- Chart.js
- FPDF
- Font Awesome
- XAMPP

---

## 📂 Proje Yapısı

```text
FinTrack/
│
├── config/
├── css/
├── includes/
├── uploads/
│
├── dashboard.php
├── incomes.php
├── expenses.php
├── goals.php
├── reports.php
├── profil.php
├── login.php
├── register.php
└── ...
```

---

## 📸 Modüller

- Dashboard
- Gelir Yönetimi
- Gider Yönetimi
- Tasarruf Hedefleri
- Profil Yönetimi
- Grafik ve Analiz
- Finansal Raporlama

---

## ⚙️ Kurulum

1. Projeyi bilgisayarınıza indirin.
2. XAMPP içerisindeki **htdocs** klasörüne kopyalayın.
3. MySQL üzerinde **FinTrack** veritabanını oluşturun.
4. SQL dosyasını phpMyAdmin üzerinden içe aktarın.
5. `config/database.php` dosyasındaki veritabanı bağlantı bilgilerini düzenleyin.
6. Apache ve MySQL servislerini çalıştırın.
7. Tarayıcıdan aşağıdaki adresi açın.

```text
http://localhost/FinTrack
```

---

## 👩‍💻 Geliştirici

**Nagihan Bölükbaş**

İstanbul Gelişim Üniversitesi  
Web Tasarımı ve Kodlama

---

## 📄 Lisans

Bu proje, eğitim ve zorunlu yaz stajı kapsamında geliştirilmiş olup ticari kullanım amacı taşımamaktadır.