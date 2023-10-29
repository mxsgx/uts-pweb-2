<?php

require_once __DIR__ . '/../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mata Kuliah</title>
    <!-- Sisipkan Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 gap-4">
            <h2 class="mb-0">Tambah Mata Kuliah</h2>
            <a class="btn btn-secondary" href="<?= url('/mata-kuliah'); ?>" role="button">Kembali</a>
        </div>
        
        <form class="card" action="<?= url('/mata-kuliah/store.php') ?>" method="post">
            <div class="card-header">Form Tambah Mata Kuliah</div>
            <div class="card-body">
                <?php flash_notification(); ?>

                <div class="mb-3">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" class="form-control" id="kode" name="kode" placeholder="contoh: ABCD01" required>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="contoh: Perancangan Web II" required>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Sisipkan Bootstrap JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
</body>
</html>