<?php
session_start();
if (!isset($_SESSION['id'])) header("Location: index.php");
require 'koneksi.php';

$user_id = $_SESSION['id'];

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$tugas_id = intval($_GET['id']);

// Ambil data tugas berdasarkan id dan user
$stmt = $conn->prepare("SELECT * FROM tugas WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $tugas_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Tugas tidak ditemukan atau kamu tidak berhak mengedit.";
    exit;
}

$tugas = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $nama = $_POST['nama_tugas'];
    $desk = $_POST['deskripsi'];
    $tgl = $_POST['deadline'];

    // Handle file upload jika ada
    $file_path = $tugas['file_tugas']; // simpan file lama kalau tidak diubah

    if (isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] == 0) {
        $allowed_ext = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        $file_name = $_FILES['file_tugas']['name'];
        $file_tmp = $_FILES['file_tugas']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed_ext)) {
            $new_file_name = uniqid('tugas_') . '.' . $ext;
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
            move_uploaded_file($file_tmp, $upload_dir . $new_file_name);

            // Hapus file lama jika ada
            if ($file_path && file_exists($file_path)) {
                unlink($file_path);
            }

            $file_path = $upload_dir . $new_file_name;
        } else {
            echo "File tipe tidak didukung.";
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE tugas SET nama_tugas=?, deskripsi=?, deadline=?, file_tugas=? WHERE id=? AND user_id=?");
    $stmt->bind_param("ssssii", $nama, $desk, $tgl, $file_path, $tugas_id, $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Tugas</h1>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nama_tugas" value="<?= htmlspecialchars($tugas['nama_tugas']) ?>" required>
            <textarea name="deskripsi" required><?= htmlspecialchars($tugas['deskripsi']) ?></textarea>
            <input type="date" name="deadline" value="<?= $tugas['deadline'] ?>" required>

            <p>File tugas saat ini: 
            <?php if ($tugas['file_tugas']): ?>
                <a href="<?= htmlspecialchars($tugas['file_tugas']) ?>" target="_blank">Lihat file</a>
            <?php else: ?>
                Tidak ada file
            <?php endif; ?>
            </p>

            <label>Upload file baru (opsional):</label>
            <input type="file" name="file_tugas" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

            <button type="submit" name="update">Update Tugas</button>
        </form>
        <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
