<?php
/**
 * Notification Controller
 */

class NotificationController extends Controller {
    
    private $notificationModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->notificationModel = $this->model('Notification');
    }
    
    /**
     * Display all notifications
     */
    public function index() {
        $userId = $_SESSION['admin_id'];
        
        $data['page_title'] = 'Notifications';
        $data['notifications'] = $this->notificationModel->getByUser($userId, 100);
        $data['unread_count'] = $this->notificationModel->countUnread($userId);
        
        $this->template('notifications/index', $data);
    }
    
    /**
     * Mark notification as read
     */
    public function markRead($id) {
        $this->notificationModel->markAsRead($id);
        $this->redirect('notification');
    }
    
    /**
     * Mark all as read
     */
    public function markAllRead() {
        $userId = $_SESSION['admin_id'];
        $this->notificationModel->markAllAsRead($userId);
        $this->setFlash('success', 'All notifications marked as read');
        $this->redirect('notification');
    }
    
    /**
     * Delete notification
     */
    public function delete($id) {
        $pk = 'notification_id'; // Based on database schema
        $sql = "DELETE FROM notifications WHERE {$pk} = :id";
        $stmt = $this->notificationModel->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $this->setFlash('success', 'Notification deleted');
        $this->redirect('notification');
    }
}
