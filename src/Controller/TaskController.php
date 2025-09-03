<?php
// src/Controller/TaskController.php

namespace App\Controller;

use App\Helper\Session;
use App\Service\Task\ITaskService;
use App\Service\Category\ICategoryService;
use Exception;
use DateTimeImmutable;
use Dompdf\Dompdf;
use Dompdf\Options;

class TaskController
{
    public function __construct(
        private ITaskService     $service,
        private ICategoryService $categoryService
    ) {}

    public function dashboard(): void
    {
        Session::start();
        $userId = Session::get('user_id');
        $stats  = $this->service->getDashboardStats($userId);

        $title   = 'Dashboard';
        $content = __DIR__ . '/../../public/views/dashboard.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function index(): void
    {
        Session::start();
        $userId = Session::get('user_id');
        if (! is_int($userId)) {
            header('Location: /login');
            return;
        }

        $status = $_GET['status'] ?? null;
        $sortBy = $_GET['sort']   ?? 'position';
        $search = $_GET['search'] ?? null;

        $tasks = $this->service->getAllTasks(
            $userId,
            $status,
            $sortBy,
            $search
        );

        $title   = 'Your Tasks';
        $content = __DIR__ . '/../../public/views/tasks/list.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function showCreateForm(): void
    {
        Session::start();
        $categories = $this->categoryService->getAll();

        $title   = 'Create Task';
        $content = __DIR__ . '/../../public/views/tasks/create.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function create(): void
    {
        Session::start();
        $userId = Session::get('user_id');
        $data   = [
            'title'       => $_POST['title']       ?? '',
            'description' => $_POST['description'] ?? '',
            'due_date'    => $_POST['due_date']    ?? '',
            'priority'    => $_POST['priority']    ?? 'medium',
            'category_id' => $_POST['category_id'] ?? null,
        ];

        $this->service->createTask($userId, $data);
        header('Location: /tasks');
    }

    public function showEditForm(string $id): void
    {
        Session::start();
        $taskId     = (int)$id;
        $userId     = Session::get('user_id');
        $task       = $this->service->getTask($taskId, $userId);

        if (! $task) {
            Session::flash('error', "Task #{$taskId} not found.");
            header('Location: /tasks');
            return;
        }

        // Pre-format date for <input type="date">
        $task['due_date'] = $task['due_date']
            ? (new DateTimeImmutable($task['due_date']))->format('Y-m-d')
            : '';

        $categories = $this->categoryService->getAll();

        $title   = 'Edit Task';
        $content = __DIR__ . '/../../public/views/tasks/edit.php';
        require __DIR__ . '/../../public/views/layout.php';
    }

    public function edit(string $id): void
    {
        Session::start();
        $taskId = (int)$id;
        $data   = [
            'title'       => $_POST['title']       ?? '',
            'description' => $_POST['description'] ?? '',
            'due_date'    => $_POST['due_date']    ?? '',
            'priority'    => $_POST['priority']    ?? 'medium',
            'completed'   => isset($_POST['completed']) ? 1 : 0,
            'category_id' => $_POST['category_id'] ?? null,
            'user_id'     => Session::get('user_id'),
        ];

        if (trim($data['title']) === '') {
            Session::flash('error', 'Title is required.');
            header("Location: /tasks/edit/{$taskId}");
            return;
        }

        try {
            $this->service->updateTask($taskId, $data);
            header('Location: /tasks');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            header("Location: /tasks/edit/{$taskId}");
        }
    }

    public function delete(string $id): void
    {
        Session::start();
        $taskId = (int)$id;
        $userId = Session::get('user_id');

        try {
            $this->service->deleteTask($taskId, $userId);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        header('Location: /tasks');
        exit;
    }

    public function toggle(): void
    {
        Session::start();
        $userId    = Session::get('user_id');
        $id        = (int)($_POST['id'] ?? 0);
        $completed = isset($_POST['completed']);

        $this->service->toggleComplete($id, $userId, $completed);
        header('Location: /tasks');
    }

    public function reorder(): void
    {
        Session::start();
        $userId = Session::get('user_id');

        if (! is_int($userId)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $order   = $payload['order'] ?? [];

        if (! is_array($order)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid payload']);
            return;
        }

        $this->service->reorderTasks($userId, $order);
        http_response_code(204);
    }

    public function exportCsv(): void
    {
        Session::start();
        $userId = Session::get('user_id');
        $tasks  = $this->service->getAllTasks($userId, null, 'position', null);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="tasks.csv"');

        $output = fopen('php://output', 'w');
        // BOM for UTF-8 in Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header row
        fputcsv($output, [
            'Category','Title','Description','Due Date','Priority','Status','Position'
        ]);

        foreach ($tasks as $task) {
            fputcsv($output, [
                $task['category_name'] ?? '',
                $task['title'],
                $task['description'],
                $task['due_date'],
                ucfirst($task['priority']),
                $task['completed'] ? 'Completed' : 'Pending',
                $task['position'],
            ]);
        }
        fclose($output);
        exit;
    }

    /**
     * GET /tasks/export/pdf
     */
    public function exportPdf(): void
    {
        Session::start();
        $userId = Session::get('user_id');
        $tasks  = $this->service->getAllTasks($userId, null, 'position', null);

        // Render HTML via a simple template
        ob_start();
        require __DIR__ . '/../../public/views/tasks/pdf.php';
        $html = ob_get_clean();

        // Dompdf setup
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Send PDF to browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="tasks.pdf"');
        echo $dompdf->output();
        exit;
    }
}
