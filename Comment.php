<?php 
class Comment {
    private $id;
    private $taskId;
    private $userId;
    private $content;
    private $createdAt;

    // Getters
    public function getId() { return $this->id; }
    public function getTaskId() { return $this->taskId; }
    public function getUserId() { return $this->userId; }
    public function getContent() { return $this->content; }
    public function getCreatedAt() { return $this->createdAt; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setTaskId($taskId) { $this->taskId = $taskId; }
    public function setUserId($userId) { $this->userId = $userId; }
    public function setContent($content) { $this->content = $content; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
}

