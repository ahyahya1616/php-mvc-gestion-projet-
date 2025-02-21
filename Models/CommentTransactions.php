<?php 
include_once 'Models/Comment.php';
include_once 'Models/DatabaseManager.php';

Class CommentTransactions{
private $pdo;

public function __construct(DatabaseManager $dbManager){
 $this->pdo=$dbManager->getConnection();
}
public function getCommentsByTaskId($taskId) {
    $stmt = $this->pdo->prepare('SELECT c.content, c.created_at, u.name as user_name 
                                FROM comments c 
                                JOIN users u ON c.user_id = u.id 
                                WHERE c.task_id = ? 
                                ORDER BY c.created_at DESC');
    $stmt->execute([$taskId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function addComment($taskId, $userId, $content) {
    $stmt = $this->pdo->prepare('INSERT INTO comments (task_id, user_id, content) VALUES (?, ?, ?)');
    return $stmt->execute([$taskId, $userId, $content]);
}

}