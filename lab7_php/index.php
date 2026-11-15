<?php 
// Panggil file proses.php agar variabel $errors dan $success_data bisa dibaca di sini
require_once 'proses.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendataan Mahasiswa</title>
    <!-- Tailwind CSS CDN untuk tampilan modern dan elegan -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl shadow-2xl p-8 text-white">
        
        <!-- Header Judul -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight">Form Mahasiswa</h1>
            <p class="text-slate-300 text-sm mt-1">Silakan isi data diri Anda dengan benar dan valid.</p>
        </div>

        <!-- Jika Data Berhasil Dikirim dan Validasi Sukses -->
        <?php if (!empty($success_data)): ?>
            <div class="bg-emerald-500/20 border border-emerald-500/50 rounded-xl p-6 mb-6 animate-fade-in">
                <div class="flex items-center space-x-3 mb-4">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <h2 class="text-lg font-semibold text-emerald-300">Data Berhasil Divalidasi!</h2>
                </div>
                <ul class="space-y-2 text-sm text-slate-200">
                    <li><strong class="text-white">Nama:</strong> <?= $success_data['nama']; ?></li>
                    <li><strong class="text-white">NIM:</strong> <?= $success_data['nim']; ?></li>
                    <li><strong class="text-white">Program Studi:</strong> <?= $success_data['prodi']; ?></li>
                    <li><strong class="text-white">Semester:</strong> <?= $success_data['semester']; ?></li>
                    <li><strong class="text-white">Email:</strong> <?= $success_data['email']; ?></li>
                    <li><strong class="text-white">Nilai Tugas:</strong> <?= htmlspecialchars((string) $success_data['nilai_tugas'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong class="text-white">Nilai UTS:</strong> <?= htmlspecialchars((string) $success_data['nilai_uts'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong class="text-white">Nilai UAS:</strong> <?= htmlspecialchars((string) $success_data['nilai_uas'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong class="text-white">Rata-rata:</strong> <?= htmlspecialchars($success_data['rata_rata'], ENT_QUOTES, 'UTF-8'); ?></li>
                    <li><strong class="text-white">Simulasi Biaya Kuliah:</strong> <?= htmlspecialchars($success_data['biaya_format'], ENT_QUOTES, 'UTF-8'); ?></li>
                </ul>
                <div class="mt-6">
                    <a href="index.php" class="block w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white font-medium py-2 px-4 rounded-xl transition duration-200 shadow-lg shadow-emerald-900/40">
                        Input Data Baru
                    </a>
                </div>
            </div>
        <?php else: ?>

            <!-- Form Input dengan Metode POST -->
            <form action="" method="POST" class="space-y-5">
                
                <!-- Nama -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES); ?>" 
                        class="w-full bg-slate-800/60 border <?= isset($errors['nama']) ? 'border-rose-500' : 'border-slate-700'; ?> rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                        placeholder="Contoh: Taufikurahman">
                    <?php if (isset($errors['nama'])): ?>
                        <p class="text-rose-400 text-xs mt-1"><?= $errors['nama']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- NIM -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">NIM</label>
                    <input type="text" name="nim" value="<?= htmlspecialchars($_POST['nim'] ?? '', ENT_QUOTES); ?>" 
                        class="w-full bg-slate-800/60 border <?= isset($errors['nim']) ? 'border-rose-500' : 'border-slate-700'; ?> rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                        placeholder="Contoh: 312310001">
                    <?php if (isset($errors['nim'])): ?>
                        <p class="text-rose-400 text-xs mt-1"><?= $errors['nim']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Program Studi -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Program Studi</label>
                    <select name="prodi" class="w-full bg-slate-800/60 border <?= isset($errors['prodi']) ? 'border-rose-500' : 'border-slate-700'; ?> rounded-xl px-4 py-2.5 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="" disabled selected>Pilih Program Studi</option>
                        <option value="Informatika" <?= (isset($_POST['prodi']) && $_POST['prodi'] == 'Informatika') ? 'selected' : ''; ?>>Informatika</option>
                        <option value="Teknik Industri" <?= (isset($_POST['prodi']) && $_POST['prodi'] == 'Teknik Industri') ? 'selected' : ''; ?>>Teknik Industri</option>
                        <option value="Teknik Mesin" <?= (isset($_POST['prodi']) && $_POST['prodi'] == 'Teknik Mesin') ? 'selected' : ''; ?>>Teknik Mesin</option>
                        <option value="Manajemen" <?= (isset($_POST['prodi']) && $_POST['prodi'] == 'Manajemen') ? 'selected' : ''; ?>>Manajemen</option>
                    </select>
                    <?php if (isset($errors['prodi'])): ?>
                        <p class="text-rose-400 text-xs mt-1"><?= $errors['prodi']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Semester (1 - 14)</label>
                    <input type="number" name="semester" value="<?= htmlspecialchars($_POST['semester'] ?? '', ENT_QUOTES); ?>" 
                        class="w-full bg-slate-800/60 border <?= isset($errors['semester']) ? 'border-rose-500' : 'border-slate-700'; ?> rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                        placeholder="Contoh: 4">
                    <?php if (isset($errors['semester'])): ?>
                        <p class="text-rose-400 text-xs mt-1"><?= $errors['semester']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES); ?>" 
                        class="w-full bg-slate-800/60 border <?= isset($errors['email']) ? 'border-rose-500' : 'border-slate-700'; ?> rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                        placeholder="nama@email.com">
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-rose-400 text-xs mt-1"><?= $errors['email']; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Nilai -->
                <?php foreach (['tugas' => 'Tugas', 'uts' => 'UTS', 'uas' => 'UAS'] as $komponen => $label): ?>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Nilai <?= $label; ?> (0 - 100)</label>
                        <input type="number" name="nilai_<?= $komponen; ?>" min="0" max="100" step="any"
                            value="<?= htmlspecialchars($_POST['nilai_' . $komponen] ?? '', ENT_QUOTES); ?>"
                            class="w-full bg-slate-800/60 border <?= isset($errors[$komponen]) ? 'border-rose-500' : 'border-slate-700'; ?> rounded-xl px-4 py-2.5 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Masukkan nilai <?= strtolower($label); ?>">
                        <?php if (isset($errors[$komponen])): ?>
                            <p class="text-rose-400 text-xs mt-1"><?= htmlspecialchars($errors[$komponen], ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Tombol Kirim -->
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold py-3 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition duration-200 mt-2">
                    Kirim & Validasi Data
                </button>
            </form>
        <?php endif; ?>

    </div>

</body>
</html>