<?php
// proses.php

$errors = [];$success_data = null;

// ==========================================
// 3. FUNGSI UNTuk MENGUBAH FORMAT HARGA KE RUPIAH
// ==========================================
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// ==========================================
// 2. CONTOH PENCARIAN MENGGUNAKAN GET (DARI ARRAY DATA)
// ==========================================
// Daftar array data mahasiswa sederhana untuk simulasi pencarian GET
$data_mahasiswa_db = [
    ["nim" => "312310001", "nama" => "Taufikurahman Ardiyansyah", "prodi" => "Informatika"],
    ["nim" => "312310002", "nama" => "Siti Nurainah", "prodi" => "Manajemen"],
    ["nim" => "312310003", "nama" => "Dwi Afandi", "prodi" => "Informatika"],
    ["nim" => "312310004", "nama" => "Salamun Nuzulul", "prodi" => "Teknik Industri"]
];

$hasil_pencarian = null;
if (isset($_GET['cari_nama']) && !empty(trim($_GET['cari_nama']))) {
    $keyword = strtolower(trim($_GET['cari_nama']));
    foreach ($data_mahasiswa_db as$mhs) {
        // Cari kecocokan nama (mengandung keyword yang dicari)
        if (strpos(strtolower($mhs['nama']),$keyword) !== false) {
            $hasil_pencarian[] =$mhs;
        }
    }
}

// ==========================================
// PEMPROSESAN FORM UTAMA (METODE POST)
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama        = trim($_POST['nama'] ?? '');
    $nim         = trim($_POST['nim'] ?? '');
    $prodi       = trim($_POST['prodi'] ?? '');
    $semester    = trim($_POST['semester'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    
    // 1. Tiga komponen nilai
    $nilai_tugas = trim($_POST['nilai_tugas'] ?? '');
    $nilai_uts   = trim($_POST['nilai_uts'] ?? '');
    $nilai_uas   = trim($_POST['nilai_uas'] ?? '');

    // Validasi Kolom Wajib
    if (empty($nama))$errors['nama'] = "Nama lengkap wajib diisi.";
    if (empty($prodi))$errors['prodi'] = "Program studi wajib diisi.";
    if (empty($semester))$errors['semester'] = "Semester wajib diisi.";
    if (empty($email))$errors['email'] = "Alamat email wajib diisi.";

    // 4. Validasi NIM: Hanya berisi angka dan panjang tepat 9 karakter
    if (empty($nim)) {$errors['nim'] = "NIM wajib diisi.";
    } elseif (!ctype_digit($nim)) {$errors['nim'] = "NIM hanya boleh berisi karakter angka saja.";
    } elseif (strlen($nim) !== 9) {$errors['nim'] = "Panjang NIM harus tepat 9 digit (contoh: 312310001).";
    }

    // Validasi Semester (Bilangan bulat 1 - 14)
    if (!empty($semester)) {
        if (filter_var($semester, FILTER_VALIDATE_INT) === false) {$errors['semester'] = "Semester harus berupa bilangan bulat.";
        } else {
            $sem_int = (int)$semester;
            if ($sem_int < 1 || $sem_int > 14) {$errors['semester'] = "Rentang semester yang valid adalah 1 sampai 14.";
            }
        }
    }

    // Validasi Format Email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {$errors['email'] = "Format alamat email tidak valid.";
    }

    // Validasi 3 Komponen Nilai (Harus angka antara 0 - 100)
    $komponen_nilai = [
        'tugas' => $nilai_tugas,
        'uts'   => $nilai_uts,
        'uas'   => $nilai_uas
    ];

    foreach ($komponen_nilai as $key =>$val) {
        if ($val === "" || !is_numeric($val)) {$errors[$key] = ucfirst($key) . " harus diisi dengan angka.";
        } else {
            $num = (float)$val;
            if ($num < 0 || $num > 100) {$errors[$key] = ucfirst($key) . " harus berada dalam rentang 0 sampai 100.";
            }
        }
    }

    // Jika lolos semua validasi, hitung rata-rata dan siapkan data sukses
    if (empty($errors)) {
        // 1. Perhitungan nilai rata-rata dari tiga komponen
        $rata_rata = ((float)$nilai_tugas + (float)$nilai_uts + (float)$nilai_uas) / 3;

        // Contoh simulasi data keuangan/biaya kuliah menggunakan fungsi format Rupiah (poin 3)
        $simulasi_biaya = 3500000;

        $success_data = [
            'nama'           => htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'), 'nim'            => htmlspecialchars($nim, ENT_QUOTES, 'UTF-8'),
            'prodi'          => htmlspecialchars($prodi, ENT_QUOTES, 'UTF-8'), 'semester'       => htmlspecialchars($semester, ENT_QUOTES, 'UTF-8'),
            'email'          => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'), 'nilai_tugas'    =>$nilai_tugas,
            'nilai_uts'      => $nilai_uts,
            'nilai_uas'      => $nilai_uas,
            'rata_rata'      => number_format($rata_rata, 2), 'biaya_format'   => formatRupiah($simulasi_biaya)
        ];
    }
}
?>