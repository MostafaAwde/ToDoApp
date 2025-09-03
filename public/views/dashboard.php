<?php
// public/views/dashboard.php
/**
 * Expects $stats = [
 *   'total' => int,
 *   'completed' => int,
 *   'pending' => int
 * ];
 */
?>
<div class="row mb-4">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <h1>Dashboard</h1>
    <a href="/tasks/create" class="btn btn-primary">New Task</a>
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <div class="card text-white bg-info h-100">
      <div class="card-body">
        <h5 class="card-title">Total Tasks</h5>
        <p class="display-6"><?= $stats['total'] ?></p>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card text-white bg-success h-100">
      <div class="card-body">
        <h5 class="card-title">Completed</h5>
        <p class="display-6"><?= $stats['completed'] ?></p>
      </div>
    </div>
  </div>

  <div class="col-md-4 mb-3">
    <div class="card text-white bg-warning h-100">
      <div class="card-body">
        <h5 class="card-title">Pending</h5>
        <p class="display-6"><?= $stats['pending'] ?></p>
      </div>
    </div>
  </div>
</div>
