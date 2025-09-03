function initTaskReorder() {
  const tbody = document.querySelector('#task-list tbody');
  if (!tbody) return;

  Sortable.create(tbody, {
    handle: '.drag-handle',
    animation: 150,
    onEnd() {
      const order = Array.from(tbody.children)
                         .map(row => row.dataset.id);
      fetch('/tasks/reorder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ order })
      });
    }
  });
}

// Auto-run if loaded directly
document.addEventListener('DOMContentLoaded', initTaskReorder);
