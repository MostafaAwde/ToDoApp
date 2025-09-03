<?php
// public/views/tasks/list.php

use App\Helper\Session;

Session::start();
?>
<h2 class="mb-4">Your Tasks</h2>

<!-- Export buttons -->
<div class="mb-3">
  <a href="/tasks/export/csv" class="btn btn-sm btn-outline-success me-2">
    <i class="bi bi-file-earmark-spreadsheet"></i> Export CSV
  </a>
  <a href="/tasks/export/pdf" class="btn btn-sm btn-outline-danger">
    <i class="bi bi-file-earmark-pdf"></i> Export PDF
  </a>
</div>

<!-- your existing Search form… -->
<form method="GET" action="/tasks" class="mb-3">
  <div class="input-group">
    <input
      type="text"
      name="search"
      class="form-control rounded-start"
      placeholder="Search tasks…"
      value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
    >
    <button type="submit" class="btn btn-outline-primary rounded-end">
      Search
    </button>
  </div>
</form>

<?php if (empty($tasks)): ?>
  <p>You have no tasks yet. <a href="/tasks/create">Create one now</a>.</p>
<?php else: ?>
  <table id="task-list" class="table table-striped">
    <thead>
      <tr>
        <th></th>
        <th>Category</th>
        <th>Title</th>
        <th>Due Date</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php $today = new \DateTimeImmutable('today'); ?>
      <?php foreach ($tasks as $task): ?>
        <?php
          $dueDate   = $task['due_date']
                     ? new \DateTimeImmutable($task['due_date'])
                     : null;
          $isOverdue = $task['is_overdue']
             ?? ($dueDate && $dueDate < $today && !$task['completed']);
          $rowClass  = $isOverdue ? 'table-warning' : '';
          $dateClass = $isOverdue ? 'text-warning fw-bold' : '';
          $dueText   = $dueDate ? $dueDate->format('M j, Y') : '—';
          $taskId    = (int)$task['task_id'];
        ?>
        <tr class="<?= $rowClass ?>" data-id="<?= $taskId ?>" draggable="true">
          <td class="drag-handle" style="cursor: grab;">☰</td>
          <td><?= htmlspecialchars($task['category_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($task['title']) ?></td>
          <td class="<?= $dateClass ?>"><?= htmlspecialchars($dueText) ?></td>
          <td><?= htmlspecialchars(ucfirst($task['priority'])) ?></td>
          <td>
            <?= $task['completed']
               ? '<span class="badge bg-success">Completed</span>'
               : '<span class="badge bg-secondary">Pending</span>' ?>
          </td>
          <td>
            <a
              href="/tasks/edit/<?= $taskId ?>"
              class="btn btn-sm btn-outline-primary me-1"
            >Edit</a>

            <form
              method="POST"
              action="/tasks/delete/<?= $taskId ?>"
              class="d-inline delete-form"
            >
              <button
                type="button"
                class="btn btn-sm btn-outline-danger btn-delete"
                data-task-title="<?= htmlspecialchars($task['title']) ?>"
              >
                Delete
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Delete Confirmation Modal -->
  <div
    class="modal fade"
    id="confirmDeleteModal"
    tabindex="-1"
    aria-labelledby="confirmDeleteLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete
          "<strong id="modalTaskTitle"></strong>"?
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >Cancel</button>
          <button
            type="button"
            class="btn btn-danger"
            id="modalConfirmBtn"
          >Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let formToSubmit = null;
      const deleteButtons = document.querySelectorAll('.btn-delete');
      const modalEl = document.getElementById('confirmDeleteModal');
      const modal = new bootstrap.Modal(modalEl);
      const modalTitle = document.getElementById('modalTaskTitle');
      const confirmBtn = document.getElementById('modalConfirmBtn');

      deleteButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          formToSubmit = btn.closest('form');
          modalTitle.textContent = btn.dataset.taskTitle;
          modal.show();
        });
      });

      confirmBtn.addEventListener('click', () => {
        if (formToSubmit) {
          formToSubmit.submit();
        }
      });
    });
  </script>

<?php endif; ?>
