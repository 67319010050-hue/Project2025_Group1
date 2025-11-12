<?php
session_start();
include 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $role = $_POST['role'];
  $password = $_POST['password'];

  if (!empty($password)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET username='$username', role='$role', password='$password' WHERE id=$id";
  } else {
    $sql = "UPDATE users SET username='$username', role='$role' WHERE id=$id";
  }

  if ($conn->query($sql)) {
    echo "<script>alert('อัปเดตข้อมูลสำเร็จ');window.location='admin_dashboard.php';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขผู้ใช้</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <form method="post" class="bg-white p-8 rounded-2xl shadow-md w-80">
    <h2 class="text-2xl font-bold text-center mb-6">แก้ไขผู้ใช้</h2>
    <input name="username" value="<?= $user['username'] ?>" required class="border p-2 w-full rounded mb-3">
    <input type="password" name="password" placeholder="เปลี่ยนรหัสผ่าน (ไม่บังคับ)" class="border p-2 w-full rounded mb-3">
    <select name="role" class="border p-2 w-full rounded mb-3">
      <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
      <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>
    <button type="submit" class="bg-yellow-500 text-white w-full py-2 rounded hover:bg-yellow-600">บันทึก</button>
    <p class="text-sm text-center mt-4"><a href="admin_dashboard.php" class="text-gray-500">← กลับ</a></p>
  </form>
</body>
</html>
