<!--Joseph Xavier Tan - 2572033-->
<?php
include 'koneksi.php';

$pesan = "";
$jenis_pesan = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $asal = trim($_POST['asal']);
    $komentar = trim($_POST['komentar']);
    if (empty($nama) || empty($asal) || empty($komentar)) {
        $pesan = "Semua field harus diisi!";
        $jenis_pesan = "danger";
    } else {
        try {
            $sql = "INSERT INTO buku_tamu (nama, asal, komentar) VALUES (:nama, :asal, :komentar)";
            $stmt_insert = $pdo->prepare($sql);
            $stmt_insert->execute(['nama' => $nama, 'asal' => $asal, 'komentar' => $komentar]);

            $pesan = "Data berhasil disimpan!";
            $jenis_pesan = "success";
        } catch (PDOException $e) {
            $pesan = "Gagal menyimpan data: " . $e->getMessage();
            $jenis_pesan = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - 2572033</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .comment-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .comment-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .waktu-text {
            font-size: 0.85em;
            color: #888;
        }

        .btn{
            background-color: #7C3AED;
            color: white;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="mb-4">Buku Tamu</h2>

        <?php if (!empty($pesan)): ?>
            <div class="alert alert-<?= $jenis_pesan ?>" role="alert">
                <?= $pesan ?>
            </div>
        <?php endif; ?>

        <div class="card p-4 mb-5">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama">
                </div>
                <div class="mb-3">
                    <label for="asal" class="form-label">Asal Kota</label>
                    <input type="text" class="form-control" id="asal" name="asal">
                </div>
                <div class="mb-3">
                    <label for="komentar" class="form-label">Komentar</label>
                    <textarea class="form-control" id="komentar" name="komentar" rows="4"></textarea>
                </div>
                <button type="submit" class="btn">Kirim Komentar</button>
            </form>
        </div>

        <h3 class="mb-4">Komentar Tamu</h3>

        <div class="card p-4">
            <?php
            $stmt = $pdo->query("SELECT * FROM buku_tamu");
            $total_komentar = $stmt->rowCount();

            echo "<p class='fw-bold mb-4'>Total Komentar: " . $total_komentar . "</p>";

            if ($total_komentar > 0) {
                $query_tampil = "SELECT * FROM buku_tamu ORDER BY waktu DESC";
                $result = mysqli_query($conn, $query_tampil);

                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="comment-item">
                        <h5><?= htmlspecialchars($row['nama']) ?> <span class="text-muted fs-6 fw-normal">dari
                                <?= htmlspecialchars($row['asal']) ?></span></h5>
                        <div class="waktu-text mb-2"><?= htmlspecialchars($row['waktu']) ?></div>
                        <p class="mb-0">"<?= nl2br(htmlspecialchars($row['komentar'])) ?>"</p>
                    </div>
                    <?php
                }
            } else {
                echo "<p class='text-muted'>Belum ada komentar</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>