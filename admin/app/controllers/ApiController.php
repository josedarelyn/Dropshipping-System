<?php
/**
 * API Controller
 * Handles AJAX/API requests
 */

class ApiController extends Controller {
    
    private $notificationModel;
    
    public function __construct() {
        $this->requireLogin();
        header('Content-Type: application/json');
        $this->notificationModel = $this->model('Notification');
    }
    
    /**
     * Get notifications for logged-in user
     */
    public function notifications() {
        $userId = $_SESSION['admin_id'];
        
        // Get unread notifications
        $unreadNotifications = $this->notificationModel->getUnreadByUser($userId);
        $unreadCount = $this->notificationModel->countUnread($userId);
        
        // Get recent notifications (last 10)
        $recentNotifications = $this->notificationModel->getByUser($userId, 10);
        
        $response = [
            'success' => true,
            'count' => $unreadCount,
            'unread' => $unreadNotifications,
            'recent' => $recentNotifications
        ];
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Mark notification as read
     */
    public function markRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificationId = $_POST['id'] ?? null;
            
            if ($notificationId) {
                $success = $this->notificationModel->markAsRead($notificationId);
                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No ID provided']);
            }
        }
        exit;
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllRead() {
        $userId = $_SESSION['admin_id'];
        $success = $this->notificationModel->markAllAsRead($userId);
        
        echo json_encode(['success' => $success]);
        exit;
    }
    
    /**
     * Get dashboard stats
     */
    public function stats() {
        $response = [
            'success' => true,
            'stats' => [
                'orders' => 0,
                'revenue' => 0,
                'users' => 0,
                'products' => 0
            ]
        ];
        
        echo json_encode($response);
        exit;
    }
}
