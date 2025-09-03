<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Tasks</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 1em; }
    th, td { border: 1px solid #333; padding: 4px; text-align: left; }
    th { background: #f0f0f0; }
  </style>
</head>
<body>
  <h1>Your Tasks</h1>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Category</th>
        <th>Title</th>
        <th>Due Date</th>
        <th>Priority</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($tasks as $i => $task): 
        $status = $task['completed'] ? 'Completed' : 'Pending';
      ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($task['category_name'] ?? '') ?></td>
          <td><?= htmlspecialchars($task['title']) ?></td>
          <td><?= htmlspecialchars($task['due_date']) ?></td>
          <td><?= ucfirst(htmlspecialchars($task['priority'])) ?></td>
          <td><?= $status ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
