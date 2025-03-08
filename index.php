<?php
require_once 'vendor/autoload.php';
use Aries\Dbmodel\Models\TaskList;

$list = new TaskList();

// Handle form submission for adding tasks
if (isset($_POST['action'])) {
    $title = $_POST['title'] ?? '';
    $status = $_POST['status'] ?? '';
    $due_date = $_POST['due_date'] ?? '';

    if ($title && $status && $due_date) {
        if ($_POST['action'] == 'Add Task') {
            $list->createTask([
                'title' => $title,
                'status' => $status,
                'due_date' => $due_date
            ]);
        }
    } else {
        echo "All fields are required!";
    }
}
//edit task
if (isset($_POST['update'])) {
    $task_id = $_POST['task_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $status = $_POST['status'] ?? '';
    $due_date = $_POST['due_date'] ?? '';

    if ($task_id && $title && $status && $due_date) {
        $list->updateTask($task_id, [
            'title' => $title,
            'status' => $status,
            'due_date' => $due_date
        ]);
        echo "Task updated successfully!";
    } else {
        echo "All fields are required!";
    }
}

//mark task as done
if (isset($_POST['done']) && $_POST['done'] == "✓") {
    if (!isset($_POST['task_id']) || empty($_POST['task_id'])) {
        die("Error: Task ID is missing or invalid.");
    }

    $task_id = intval($_POST['task_id']); // Convert task_id to integer
    if ($task_id <= 0) {
        die("Error: Invalid task ID.");
    }
   
    $list->markDone($task_id);
}
//delete task
if (isset($_POST['delete']) && $_POST['delete'] == '✘') {
    if (!isset($_POST['task_id']) || empty($_POST['task_id'])) {
        die("Error: Task ID is missing or invalid.");
    }

    $task_id = intval($_POST['task_id']); // Convert to integer
    if ($task_id <= 0) {
        die("Error: Invalid task ID.");
    }

    // Call the delete function
    $list->deleteTask($task_id);
}


$tasks = $list->getAllTasks(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>My To-Do List</h1>
    <div class="form">
        <h2><?= isset($_POST['edit']) ? 'Edit Task' : 'Add Task' ?></h2>
        <form method="POST">
            <input type="hidden" name="task_id" id="task_id" value="<?= isset($_POST['edit']) ?
                htmlspecialchars($_POST['task_id'], ENT_QUOTES, 'UTF-8') : '' ?>">
            <input type="text" name="title" id="title" placeholder="Title" value="<?= isset($_POST['edit']) ? 
                htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
            <select name="status" id="status" required>
                <option value="Not Started" <?= isset($_POST['status']) && $_POST['status'] == 'Not Started' ? 'selected' : '' ?>>Not Started</option>
                <option value="In Progress" <?= isset($_POST['status']) && $_POST['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Completed" <?= isset($_POST['status']) && $_POST['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>  
            <input type="datetime-local" name="due_date" id="due_date" value="<?= isset($_POST['edit']) ? 
                htmlspecialchars($_POST['due_date'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
            <input type="submit" name="<?= isset($_POST['edit']) ? 'update' : 'action' ?>" value="<?= isset($_POST['edit']) 
                ? 'Update Task' : 'Add Task' ?>">
        </form>
    </div>
    <h2>Tasks</h2>
    <table class="center">
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Actions</th>      
        </tr>
        <?php foreach ($tasks as $task): ?>
            <div class="task">
                <tr>
                        <td><?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($task['status'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td> 
                        
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['task_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" name="done" value="✓">
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['task_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" name="delete" value="✘" onclick="return confirm('Are you sure you want to delete this task?');">
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['task_id'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="title" value="<?= htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="status" value="<?= htmlspecialchars($task['status'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="due_date" value="<?= htmlspecialchars($task['due_date'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="submit" name="edit" value="✎">
                        </form> 
                    </td>   
                </tr>
            </div>
        <?php endforeach; ?>
    </table>

   
</body>
</html>