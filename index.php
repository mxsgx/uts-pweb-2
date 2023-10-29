<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

$data = $db->query('SELECT * FROM mata_kuliah');

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
        <div class="mb-2">
            <?php flash_notification(); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4 gap-4">
            <h2 class="mb-0">Daftar Mata Kuliah</h2>
            <?php if (!isset($_SESSION['user'])) { ?>
                <a class="btn btn-success" href="<?= url('/login.php'); ?>" role="button">Login</a>
            <?php } else { ?>
                <div class="d-flex gap-2">
                    <a class="btn btn-success" href="<?= url('/mata-kuliah'); ?>" role="button">Kelola</a>
                    <a class="btn btn-danger" href="logout.php">Logout</a>
                </div>
            <?php } ?>
        </div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Mata Kuliah</th>
                    <th scope="col">Kode</th>
                    <th scope="col">Deskripsi</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($data->num_rows > 0) { ?>
                    <?php while ($mata_kuliah = $data->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $mata_kuliah['id_mk']; ?></td>
                            <td><?= $mata_kuliah['nama_mk']; ?></td>
                            <td><?= $mata_kuliah['kode_mk']; ?></td>
                            <td><?= htmlspecialchars($mata_kuliah['deskripsi_mk']); ?></td>
                            <td class="d-flex gap-2">
                                <a href="<?= url('/mata-kuliah/view.php?id=' . $mata_kuliah['id_mk']) ?>" class="text-secondary">Lihat</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">Mata Kuliah Kosong</td>
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

$data->close();