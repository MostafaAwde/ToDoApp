<?php
// public/views/layout.php
use App\Helper\Session;

Session::start();
$userName = Session::get('user_name');
$flashSuccess = Session::flash('success');
$flashError   = Session::flash('error');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= htmlspecialchars($title ?? 'To-Do App') ?></title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet">

  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

  
</head>

<body class="d-flex flex-column min-vh-100">

  <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <div class="container">
      <a class="navbar-brand" href="/tasks">To-Do App</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav ms-auto">
          <?php if ($userName): ?>
            <li class="nav-item">
              <span class="nav-link">Hello, <?= htmlspecialchars($userName) ?></span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/tasks">My Tasks</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-danger" href="/logout">Logout</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/login">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/signup">Sign Up</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container mb-5">
    <?php if ($flashSuccess): ?>
      <div class="alert alert-success"><?= htmlspecialchars($flashSuccess) ?></div>
    <?php endif; ?>
    <?php if ($flashError): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($flashError) ?></div>
    <?php endif; ?>

    <?php require $content; ?>
  </main>

  <footer class="mt-auto py-3 bg-light">
    <div class="container text-center">
      <small>&copy; <?= date('Y') ?> To-Do App</small>
    </div>
  </footer>

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/task-reoder.js"></script>
</body>

</html>