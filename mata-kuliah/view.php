<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../functions.php';

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
    <title>Edit Mata Kuliah "<?= $mata_kuliah['nama_mk']; ?>"</title>
    <!-- Sisipkan Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="mb-2">
            <?php flash_notification(); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4 gap-4">
            <h2 class="mb-0">Mata Kuliah "<?= $mata_kuliah['nama_mk']; ?>"</h2>
        </div>
        
        <div class="card mb-4" action="<?= url('/mata-kuliah/update.php?id=' . $mata_kuliah['id_mk']) ?>" method="post">
            <div class="card-header">Detail Mata Kuliah</div>
            <div class="card-body">
                <div class="mb-3">
                    <b>Kode</b>
                    <p><?= $mata_kuliah['kode_mk']; ?></p>
                </div>

                <div class="mb-3">
                    <b>Nama</b>
                    <p><?= $mata_kuliah['nama_mk']; ?></p>
                </div>

                <div class="mb-3">
                    <b>Deskripsi</b>
                    <p><?= htmlspecialchars($mata_kuliah['deskripsi_mk']); ?></p>
                </div>
            </div>
        </div>

        <h3 class="mb-4">Materi</h3>

        <table class="table table-bordered table-responsive mb-4">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">File</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($materi->num_rows > 0) { ?>
                    <?php while ($materi_mk = $materi->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $materi_mk['id_materi']; ?></td>
                            <td><?= $materi_mk['judul_materi']; ?></td>
                            <td><?= $materi_mk['deskripsi_materi']; ?></td>
                            <td><a href="<?= url('/materi-kuliah/materi/files/' . $materi_mk['file_materi']) ?>" target="_blank">Lihat</a></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" class="text-center">Materi Kosong</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Sisipkan Bootstrap JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
</body>
</html>

<?php

$stmt->close();