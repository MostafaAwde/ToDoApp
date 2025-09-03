<?php
// public/views/tasks/create.php

use App\Helper\Session;

Session::start();
?>
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h2 class="card-title mb-4">Create Task</h2>
        <form method="POST" action="/tasks/create" class="row g-3">
          
          <div class="col-12">
            <label class="form-label">Title</label>
            <input
              type="text"
              name="title"
              class="form-control"
              required
            >
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea
              name="description"
              class="form-control"
              rows="3"
            ></textarea>
          </div>

          <div class="col-md-6">
            <label class="form-label">Due Date</label>
            <input
              type="date"
              name="due_date"
              class="form-control"
            >
          </div>

          <div class="col-md-6">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
              <option value="low">Low</option>
              <option value="medium" selected>Medium</option>
              <option value="high">High</option>
            </select>
          </div>

          <div class="col-12">
            <label for="category_id" class="form-label">Category</label>
            <select
              id="category_id"
              name="category_id"
              class="form-select"
            >
              <option value="">— None —</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat->getId()) ?>">
                  <?= htmlspecialchars($cat->getName()) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 text-end">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="/tasks" class="btn btn-outline-secondary">Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
