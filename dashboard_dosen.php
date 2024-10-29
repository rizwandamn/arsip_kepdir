<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard_dosen.css">

</head>
<body>
    <div class="container">
        <h1 class="mt-4">Dashboard Dosen</h1>

        <!-- Form sortir kategori, jenis surat dan search -->
        <form method="post" action="">
            <div class="row mb-4">
                <div class="col">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="pendidikan" <?=$kategori_filter == 'pendidikan' ? 'selected' : '';?>>Pendidikan</option>
                        <option value="penelitian" <?=$kategori_filter == 'penelitian' ? 'selected' : '';?>>Penelitian</option>
                        <option value="pengabdian" <?=$kategori_filter == 'pengabdian' ? 'selected' : '';?>>Pengabdian</option>
                        <option value="lainnya" <?=$kategori_filter == 'lainnya' ? 'selected' : '';?>>Lainnya</option>
                    </select>
                </div>
                <div class="col">
                    <select name="jenis" class="form-select">
                        <option value="">Semua Jenis</option>
                        <option value="surat_keputusan" <?=$jenis_filter == 'surat_keputusan' ? 'selected' : '';?>>Surat Keputusan</option>
                        <option value="surat_tugas" <?=$jenis_filter == 'surat_tugas' ? 'selected' : '';?>>Surat Tugas</option>
                    </select>
                </div>
                <div class="col">
                    <input type="text" name="search" class="form-control" placeholder="Cari dokumen..." value="<?=htmlspecialchars($search_term);?>">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Cari & Sortir</button>
                </div>
            </div>
        </form>

        <!-- Menampilkan dokumen dalam bentuk kartu -->
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?=htmlspecialchars($row['title']);?></h5>
                                <p class="card-text"><strong>No. Surat:</strong> <?=htmlspecialchars($row['no_surat']);?></p>
                                <p class="card-text"><strong>Tanggal Surat:</strong> <?=htmlspecialchars($row['tanggal_surat']);?></p>
                                <p class="card-text"><strong>Kategori:</strong> <?=ucfirst(htmlspecialchars($row['kategori']));?></p>
                                <p class="card-text"><strong>Jenis:</strong> <?=ucfirst(htmlspecialchars($row['jenis']));?></p>

                                <!-- Tombol aksi -->
                                <a href="preview_dokumen.php?id=<?=$row['id_dokumen'];?>&from=dosen" class="btn btn-info btn-sm">Preview</a>
                                <a href="download_dokumen.php?id=<?=$row['id_dokumen'];?>" class="btn btn-success btn-sm">Download</a>

                                <!-- Tombol Tandai/Batal Tandai -->
                                <?php
$isMarkedQuery = "SELECT * FROM marked_dokumen WHERE id_user = ? AND id_dokumen = ?";
$isMarkedStmt = mysqli_prepare($conn, $isMarkedQuery);
mysqli_stmt_bind_param($isMarkedStmt, 'ii', $userId, $row['id_dokumen']);
mysqli_stmt_execute($isMarkedStmt);
$isMarkedResult = mysqli_stmt_get_result($isMarkedStmt);

$isMarked = mysqli_num_rows($isMarkedResult) > 0;
?>
                                <a href="mark_dokumen.php?id=<?=$row['id_dokumen'];?>" class="btn btn-secondary btn-sm">
                                    <?=$isMarked ? 'Batal Tandai' : 'Tandai';?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile;?>
            <?php else: ?>
                <p class="text-center">Tidak ada dokumen yang sesuai dengan pencarian atau filter.</p>
            <?php endif;?>
        </div>

        <!-- Tombol logout -->
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
