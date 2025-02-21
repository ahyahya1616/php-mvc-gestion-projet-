<?php 
class Notification {
    private $id;
    private $userId;
    private $title;
    private $message;
    private $isRead;
    private $createdAt;
    private $type; 
    private $relatedId; 

    public function getId() { return $this->id; }
    public function getUserId() { return $this->userId; }
    public function getTitle() { return $this->title; }
    public function getMessage() { return $this->message; }
    public function getIsRead() { return $this->isRead; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getType() { return $this->type; }
    public function getRelatedId() { return $this->relatedId; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setUserId($userId) { $this->userId = $userId; }
    public function setTitle($title) { $this->title = $title; }
    public function setMessage($message) { $this->message = $message; }
    public function setIsRead($isRead) { $this->isRead = $isRead; }
    public function setCreatedAt($createdAt) { $this->createdAt = $createdAt; }
    public function setType($type) { $this->type = $type; }
    public function setRelatedId($relatedId) { $this->relatedId = $relatedId; }
}