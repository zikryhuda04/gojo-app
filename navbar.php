<?php
session_start();
if (!isset($_SESSION['id'])) header("Location: index.php");
?>
<nav class="sidebar-nav">
  <a href="home.php" class="nav-link" data-target="home.php">Home</a>
  <a href="dashboard.php" class="nav-link" data-target="dashboard.php">Dashboard</a>
  <a href="courses.php" class="nav-link" data-target="courses.php">My Courses</a>
  <a href="profile.php" class="nav-link" data-target="profile.php">Profile</a>
  <a href="logout.php">Logout</a>
</nav>

<script>
  // Untuk efek transisi klik sidebar
  document.querySelectorAll('.nav-link').forEach(link => {
    link.addEventListener('click', e => {
      e.preventDefault();
      const target = e.target.getAttribute('href');
      document.body.classList.add('fade-out');
      setTimeout(() => {
        window.location.href = target;
      }, 500);
    });
  });

  // Tambahkan class fade-in saat load
  window.onload = () => {
    document.body.classList.add('fade-in');
  }
</script>
