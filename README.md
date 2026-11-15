# Lab7Web_PHP.

# Lab7Web_PHP.

PENJELASAN RINCI KODE PROYEK lab7_php
Proyek ini menggunakan konsep Pemisahan Logika dan Tampilan (Pemisahan Concern / MVC Sederhana), di mana file proses.php bertindak sebagai pengolah data (backend) dan index.php bertindak sebagai antarmuka pengguna (frontend).

PART 1: Penjelasan File proses.php (Logika & Validasi Server-Side)
File ini tidak menampilkan elemen HTML visual secara langsung, melainkan mengeksekusi operasi pemrograman di balik layar setiap kali ada permintaan (request) dari browser.

1. Inisialisasi Variabel Awal
PHP
$errors = [];
$success_data = null;
Maksud: Kita menyiapkan wadah penampung berupa array kosong bernama $errors untuk mencatat pesan kesalahan jika validasi gagal, dan variabel $success_data untuk menampung data yang sudah bersih dan siap ditampilkan jika validasi berhasil.

2. Fungsi Format Rupiah (formatRupiah)
PHP
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
Maksud: Ini adalah implementasi fungsi kustom (user-defined function). Fungsi ini menerima parameter berupa angka mentah (misal: 3500000). Fungsi bawaan PHP number_format($angka, 0, ',', '.') digunakan untuk memformat angka dengan ketentuan:

0 jumlah angka di belakang koma (tanpa desimal).

',' sebagai pemisah ribuan.

'.' sebagai pemisah desimal.

Hasil akhirnya digabung dengan string "Rp " di depannya.

3. Simulasi Pencarian Data Menggunakan Metode GET
PHP
$data_mahasiswa_db = [
    ["nim" => "312310001", "nama" => "Taufikurahman Ardiyansyah", "prodi" => "Informatika"],
    ...
];
Maksud: Kita membuat array multidimensi statis untuk mensimulasikan database sederhana.

PHP
$hasil_pencarian = null;
if (isset($_GET['cari_nama']) && !empty(trim($_GET['cari_nama']))) {
    $keyword = strtolower(trim($_GET['cari_nama']));
    foreach ($data_mahasiswa_db as $mhs) {
        if (strpos(strtolower($mhs['nama']), $keyword) !== false) {
            $hasil_pencarian[] = $mhs;
        }
    }
}
Cara Kerja:

isset($_GET['cari_nama']) mengecek apakah parameter URL cari_nama dikirim melalui metode GET.

trim() membuang spasi berlebih, dan strtolower() mengubah input pencarian menjadi huruf kecil agar pencarian bersifat case-insensitive (tidak sensitif huruf besar/kecil).

Perulangan foreach mencocokkan setiap nama di array dengan kata kunci menggunakan strpos(). Jika ditemukan kecocokan, data dimasukkan ke array $hasil_pencarian.

4. Pemrosesan Formulir Utama (Metode POST)
PHP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama        = trim($_POST['nama'] ?? '');
    ...
}
Maksud: Blok kode ini hanya akan dieksekusi jika pengguna menekan tombol submit pada form utama (artinya request HTTP yang masuk berjenis POST). Data diambil menggunakan null coalescing operator (?? '') untuk mencegah error Undefined Index jika variabel kosong, lalu dibersihkan dengan trim().

5. Validasi Server-Side yang Ketat
Setiap input divalidasi satu per satu di sisi server sebelum diproses lebih lanjut:

Validasi Wajib Diisi (empty): Memastikan kolom nama, prodi, semester, dan email tidak dibiarkan kosong.

Validasi NIM Ketat:

PHP
if (empty($nim)) {
    $errors['nim'] = "NIM wajib diisi.";
} elseif (!ctype_digit($nim)) {
    $errors['nim'] = "NIM hanya boleh berisi karakter angka saja.";
} elseif (strlen($nim) !== 9) {
    $errors['nim'] = "Panjang NIM harus tepat 9 digit.";
}
ctype_digit($nim) memastikan bahwa seluruh karakter di dalam string NIM benar-benar angka (0-9) tanpa ada huruf atau simbol.

strlen($nim) !== 9 memastikan panjang karakter NIM wajib pas 9 digit.

Validasi Semester (Bilangan Bulat 1 s.d. 14):

PHP
if (!empty($semester) && (!filter_var($semester, FILTER_VALIDATE_INT) || $semester < 1 || $semester > 14)) {
    $errors['semester'] = "Semester harus berupa bilangan bulat antara 1 sampai 14.";
}
Menggunakan filter_var dengan argumen FILTER_VALIDATE_INT untuk memastikan input adalah bilangan bulat murni, dilanjutkan pengecekan rentang angka minimal 1 dan maksimal 14.

Validasi Format Email:

Menggunakan filter_var($email, FILTER_VALIDATE_EMAIL) yang merupakan fungsi bawaan PHP untuk memeriksa apakah string memenuhi standar struktur email internet (memiliki simbol @ dan domain yang valid).

Validasi Tiga Komponen Nilai (Tugas, UTS, UAS):

Memakai perulangan untuk memeriksa apakah nilai diisi angka dan berada dalam rentang 0 sampai 100.

6. Perhitungan Rata-Rata dan Pengamanan Data
PHP
if (empty($errors)) {
    $rata_rata = ((float)$nilai_tugas + (float)$nilai_uts + (float)$nilai_uas) / 3;
    $success_data = [
        'nama' => htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'),
        ...
    ];
}
Maksud: Jika array $errors tetap kosong (artinya tidak ada satupun error), maka program menghitung nilai rata-rata aritmatika dari ketiga komponen nilai.

Keamanan (htmlspecialchars): Sebelum data disimpan ke $success_data, seluruh string teks dibungkus dengan htmlspecialchars(..., ENT_QUOTES, 'UTF-8'). Fungsi ini mengubah karakter spesial HTML (seperti <, >, &, ', ") menjadi entitas aman guna mencegah celah keamanan XSS (Cross-Site Scripting).

PART 2: Penjelasan File index.php (Tampilan & Antarmuka Pengguna)
File ini berisi struktur HTML dan CSS standar yang mengatur bagaimana form dan hasil pemrosesan ditampilkan ke hadapan pengguna di browser.

1. Menghubungkan File Logika
PHP
<?php require_once 'proses.php'; ?>
Maksud: Di baris paling atas, file index.php memanggil file proses.php menggunakan require_once. Hal ini memastikan seluruh variabel error, hasil pencarian GET, dan data sukses sudah siap diproses sebelum kode HTML di bawahnya dirender oleh server.

2. Styling CSS Internal
HTML
<style>
    body { font-family: Arial, sans-serif; background-color: #f4f4f9; ... }
    .container { max-width: 600px; background: #fff; ... }
    ...
</style>
Maksud: Menggunakan CSS murni di dalam tag <style> agar tampilannya rapi, berbentuk kotak kartu di tengah layar (centered container), mudah dibaca, dan tidak memerlukan koneksi internet (bekerja secara offline di XAMPP).

3. Implementasi Form Pencarian (Metode GET)
HTML
<form action="" method="GET">
    <input type="text" name="cari_nama" value="<?= htmlspecialchars($_GET['cari_nama'] ?? '', ENT_QUOTES); ?>">
    <button type="submit" class="btn">Cari</button>
</form>
Maksud: Form pencarian ini menggunakan method="GET". Ketika tombol "Cari" ditekan, parameter query akan muncul di URL browser (contoh: http://localhost/lab7_php/?cari_nama=Taufik). Nilai pada input dicetak kembali menggunakan fungsi pendek PHP <?= ... ?> agar kata kunci pencarian tidak hilang dari kolom input setelah halaman dimuat ulang.

4. Percabangan Tampilan (Kondisional Sukses vs Form Input)
PHP
<?php if (!empty($success_data)): ?>
    <!-- Kotak Hijau Hasil Data & Nilai Rata-rata -->
<?php else: ?>
    <!-- Form Input Utama POST -->
<?php endif; ?>
Maksud: Struktur kendali if-else ini mengatur pergantian tampilan secara dinamis:

Jika validasi sukses (!empty($success_data) bernilai benar), form input disembunyikan dan diganti dengan kotak hijau berisi ringkasan data mahasiswa, nilai rata-rata, dan simulasi biaya berformat Rupiah.

Jika belum disubmit atau validasi gagal, form input utama akan ditampilkan.

5. Konsep Sticky Form pada Input POST
HTML
<input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES); ?>">
Maksud: Atribut value diisi dengan data dari $_POST['nama']. Ini disebut Sticky Form, yaitu fitur agar ketika pengguna salah mengisi salah satu kolom (misal NIM kurang dari 9 digit), data pada kolom lain yang sudah terlanjur diketik tidak terhapus otomatis, sehingga pengguna tidak perlu mengetik ulang dari awal.

6. Pemunculan Pesan Error Dinamis
PHP
<?php if (isset($errors['nama'])): ?>
    <div class="error"><?= $errors['nama']; ?></div>
<?php endif; ?>
Maksud: Kode ini bertugas mengecek apakah ada catatan error khusus untuk kolom nama di dalam array $errors. Jika ada, sebuah teks peringatan berwarna merah akan dimunculkan tepat di bawah kolom input yang bersangkutan.

7. Tombol Aksi (Submit & Reset)
HTML
<button type="submit" class="btn">Kirim Data</button>
<button type="reset" class="btn btn-reset">Reset Form</button>
Tombol Submit (type="submit"): Berfungsi mengirimkan seluruh data form ke server menggunakan metode POST untuk divalidasi dan diproses oleh PHP.

Tombol Reset (type="reset"): Bekerja murni di sisi klien (client-side browser). Tombol ini bertugas membersihkan seluruh teks yang sedang diketik di dalam form kembali kosong tanpa melakukan komunikasi atau reload data ke server.

<img width="1456" height="814" alt="hasil codingan 2 2" src="https://github.com/user-attachments/assets/f0d64400-91b7-48e8-af6a-ce8cab480def" />
<img width="1600" height="782" alt="hasil codingan 2 1" src="https://github.com/user-attachments/assets/ad076e40-6930-4802-9e5e-1179ce65c0f9" />
<img width="1435" height="836" alt="hasil 4" src="https://github.com/user-attachments/assets/0d3836e8-19f6-4e38-9878-70d714eac325" />
<img width="1445" height="816" alt="hasil 1" src="https://github.com/user-attachments/assets/39dcaab4-4221-4bab-a887-44357477b98f" />
<img width="1307" height="820" alt="codingan 8 2" src="https://github.com/user-attachments/assets/23e4ebcf-b280-40f4-980f-0ceb6228b97e" />
<img width="1297" height="821" alt="codingan 8 1" src="https://github.com/user-attachments/assets/e99605e0-7e1d-4dd7-8791-cfe249242a54" />
<img width="1500" height="838" alt="codingan 7" src="https://github.com/user-attachments/assets/1f7bf6db-19b4-4980-a56f-f4d38471aec2" />
<img width="1099" height="822" alt="codingan 6" src="https://github.com/user-attachments/assets/ce71afcf-b285-4b41-8935-40415e5396ce" />
<img width="1580" height="764" alt="codingan 5" src="https://github.com/user-attachments/assets/3c864a55-3aa5-46d3-829b-11cdcc55b9ca" />
<img width="1599" height="849" alt="codingan 4" src="https://github.com/user-attachments/assets/02c6badb-d7d4-4f76-915c-39c35f7ad5c1" />
<img width="1589" height="771" alt="codingan 3" src="https://github.com/user-attachments/assets/6dbda303-e381-442c-b636-7d099db2f73e" />
<img width="1600" height="763" alt="codingan 2 2" src="https://github.com/user-attachments/assets/72405474-e826-4af6-9480-2dfc8f237f2c" />
<img width="1593" height="792" alt="codingan 2 1" src="https://github.com/user-attachments/assets/d73004f9-de07-4106-accd-977eca27688d" />
<img width="1600" height="816" alt="codingan 1" src="https://github.com/user-attachments/assets/38d6d0e8-bb01-4197-bbb8-7214b019a0ca" />
<img width="1439" height="834" alt="hasil codingan 8" src="https://github.com/user-attachments/assets/0fb7daaf-c7eb-41b4-88fc-6c9660e97f26" />
<img width="1427" height="848" alt="hasil codingan 8 3" src="https://github.com/user-attachments/assets/63ff57d8-c927-45c0-b3e6-aa617198d1f1" />
<img width="1594" height="812" alt="hasil codingan 8 2" src="https://github.com/user-attachments/assets/498e67bb-12c2-4a56-a711-8779551101de" />
<img width="1446" height="802" alt="hasil codingan 7" src="https://github.com/user-attachments/assets/6c5a59e0-21fb-45c6-bb65-51778e59cd94" />
<img width="1454" height="794" alt="hasil codingan 6" src="https://github.com/user-attachments/assets/2c016925-61eb-4520-b7c8-20fbf49afde8" />
<img width="1443" height="891" alt="hasil codingan 5" src="https://github.com/user-attachments/assets/c1b5fe15-eda3-4718-b094-06fec845dbfa" />
<img width="1442" height="851" alt="hasil codingan 4" src="https://github.com/user-attachments/assets/cb79d4fc-d2d8-4a91-a3e2-76c2a07df242" />
<img width="1444" height="851" alt="hasil codingan 3" src="https://github.com/user-attachments/assets/02e70152-041f-412a-a5a6-edd7c985f739" />
