<?php

require_once __DIR__ . '/../../database.php';
require_once __DIR__ . '/../../functions.php';

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header('Location: ' . url('/login.php'));
    exit;
}

if (!isset($_GET['id'])) {
    set_notification('Id mata kuliah tidak ada', 'danger');
    header('Location: ' . url('/mata-kuliah/index.php'));
    exit;
}

$id = (int) $_GET['id'];

$stmt = $db->prepare('SELECT * FROM mata_kuliah WHERE id_mk = ? LIMIT 1');
$stmt->bind_param('i', $id);
if (!$stmt->execute()) {
    set_notification('Gagal mengambil data mata kuliah', 'danger');
    header('Location: ' . url('/mata-kuliah/index.php'));
    exit;
}

$result = $stmt->get_result();
$mata_kuliah = $result->fetch_assoc();

$materi = $db->query("SELECT * FROM materi WHERE id_mk = $id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Materi Mata Kuliah "<?= $mata_kuliah['nama_mk']; ?>"</title>
    <!-- Sisipkan Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="mb-2">
            <?php flash_notification(); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4 gap-2">
            <h2 class="mb-0">Tambah Materi Mata Kuliah "<?= $mata_kuliah['nama_mk']; ?>"</h2>
            <div class="d-flex gap-2">
                <a class="btn btn-secondary" href="<?= url('/mata-kuliah/edit.php?id=' . $id); ?>" role="button">Kembali</a>
            </div>
        </div>

        <form class="card mb-4" action="<?= url('/mata-kuliah/materi/store.php?id=' . $mata_kuliah['id_mk']) ?>" method="post" enctype="multipart/form-data">
            <div class="card-header">Form Tambah Materi</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul</label>
                    <input type="text" class="form-control" id="judul" name="judul" placeholder="contoh: Pengenalan" required>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">File</label>
                    <input type="file" class="form-control" id="file" name="file" required accept=".doc,.docx,.pdf">
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-end gap-2">
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

<?php

$stmt->close();