<?php

namespace Aries\Dbmodel\Models;

use Aries\Dbmodel\Includes\Database;

class TaskList extends Database {
    private $db;

    public function __construct() {
        parent::__construct(); // Call the parent constructor to establish the connection
        $this->db = $this->getConnection(); // Get the connection instance
    }

    // Get all tasks from the list
    public function getAllTasks() {
        $sql = "SELECT * FROM list";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Get a single task by ID
    public function getTask($id) { 
        $sql = "SELECT * FROM list WHERE task_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    

    // Create a new task
    public function createTask($data) {
        $sql = "INSERT INTO list (title, status, due_date) VALUES (:title, :status, :due_date)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'status' => $data['status'],
            'due_date' => $data['due_date']
        ]);
        return $this->db->lastInsertId();
    }
    
    

    
    
    // Update an existing task
    public function updateTask($task_id, $data) {
        $sql = "UPDATE list SET title = :title, status = :status, due_date = :due_date WHERE task_id = :task_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'title' => $data['title'],
            'status' => $data['status'],
            'due_date' => $data['due_date'],
            'task_id' => $task_id
        ]);
        return "Task updated successfully";
    }
    
    

    public function markDone($task_id) {
        $sql = "UPDATE list SET status = 'Completed' WHERE task_id = :task_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'task_id' => $task_id
        ]);
        return "Task marked as Done";
    }
    
    
    // Delete a task
    public function deleteTask($task_id) {
        $sql = "DELETE FROM list WHERE task_id = :task_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'task_id' => $task_id
        ]);
        return "Task deleted successfully";
    }
    
}
