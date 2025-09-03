<?php
// public/views/tasks/edit.php

use App\Helper\Session;

/** @var array $task */
/** @var \App\Model\Category[] $categories */

Session::start();
$title = 'Edit Task';
?>
<h2 class="mb-4"><?= htmlspecialchars($title) ?></h2>

<?php if ($error = Session::flash('error')): ?>
  <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="/tasks/edit/<?= (int)$task['id'] ?>">
  
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input
      type="text"
      id="title"
      name="title"
      class="form-control"
      value="<?= htmlspecialchars($task['title']) ?>"
      required
    >
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea
      id="description"
      name="description"
      class="form-control"
      rows="3"
    ><?= htmlspecialchars($task['description']) ?></textarea>
  </div>

  <div class="mb-3">
    <label for="due_date" class="form-label">Due Date</label>
    <input
      type="date"
      id="due_date"
      name="due_date"
      class="form-control"
      value="<?= htmlspecialchars($task['due_date']) ?>"
    >
  </div>

  <div class="mb-3">
    <label for="priority" class="form-label">Priority</label>
    <select id="priority" name="priority" class="form-select">
      <?php foreach (['low','medium','high'] as $p): ?>
        <option
          value="<?= $p ?>"
          <?= $task['priority'] === $p ? 'selected' : '' ?>
        ><?= ucfirst($p) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select id="category_id" name="category_id" class="form-select">
      <option value="">— None —</option>
      <?php foreach ($categories as $cat): ?>
        <option
          value="<?= htmlspecialchars($cat->getId()) ?>"
          <?= $cat->getId() === ($task['category_id'] ?? null) ? 'selected' : '' ?>
        >
          <?= htmlspecialchars($cat->getName()) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-check mb-3">
    <input
      type="checkbox"
      id="completed"
      name="completed"
      class="form-check-input"
      <?= $task['completed'] ? 'checked' : '' ?>
    >
    <label class="form-check-label" for="completed">Completed</label>
  </div>

  <button type="submit" class="btn btn-primary">Save Changes</button>
  <a href="/tasks" class="btn btn-secondary ms-2">Cancel</a>
</form>
