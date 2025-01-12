<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "webasean");

// Fetch user data
$userId = 1; // Replace with dynamic session/user ID
$sql = "SELECT * FROM user WHERE id = $userId";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateFoto = false;

    // Update password if provided
    if (!empty($_POST['password'])) {
        $password = MD5($_POST['password']);
        $conn->query("UPDATE user SET password = '$password' WHERE id = $userId");
    }

    // Update photo if provided
    if (!empty($_FILES['foto']['name'])) {
        $foto = $_FILES['foto'];
        $fotoName = time() . "_" . $foto['name'];
        move_uploaded_file($foto['tmp_name'], "img/" . $fotoName);
        $conn->query("UPDATE user SET foto = '$fotoName' WHERE id = $userId");
        $updateFoto = true;
    }

    // Refresh user data
    if ($updateFoto) {
        $sql = "SELECT * FROM user WHERE id = $userId";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    }

    echo "Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-5">
    <h2>Manage Profile</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="foto" class="form-label">Profile Photo</label><br>
            <img src="img/<?= htmlspecialchars($user['foto']) ?>" alt="Profile Photo" width="150" class="mb-3">
            <input type="file" class="form-control" id="foto" name="foto">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
</body>
</html>
