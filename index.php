<?php
session_start();
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $query = $koneksi->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($query->num_rows == 1) {
        $user = $query->fetch_assoc();
        $_SESSION['id'] = $user['id'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        $_SESSION['foto'] = $user['foto_profil'];
        header("Location: dashboard.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - Gojo</title>
  <style>
  body {
    font-family: sans-serif;
    background: url('ft-1.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  .login-box {
    background: rgba(255, 255, 255, 0.15); /* transparan */
    padding: 25px;
    border-radius: 15px;
    width: 320px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px); /* efek blur */
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #fff;
  }

  input {
    width: 100%;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 8px;
    border: none;
    outline: none;
    background: rgba(255, 255, 255, 0.3);
    color: white;
  }

  input::placeholder {
    color: #eee;
  }

  button {
    width: 100%;
    padding: 10px;
    background: #4a90e2;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
  }

  button:hover {
    background: #357acc;
  }

  p {
    text-align: center;
    color: #ffbaba;
  }
</style>


</head>
<body>
  <div class="login-box">
    <h2>Login</h2>
    <form method="post">
      <input type="text" name="username" placeholder="Username" required><br>
      <input type="password" name="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
  </div>
</body>
</html>
