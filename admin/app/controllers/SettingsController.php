<?php

class SettingsController extends Controller {
    
    private $settingModel;
    
    public function __construct() {
        $this->requireLogin();
        $this->settingModel = $this->model('Setting');
    }
    
    /**
     * Settings index page with tabs
     */
    public function index() {
        $data = [
            'page_title' => 'System Settings',
            'active_tab' => $_GET['tab'] ?? 'general'
        ];
        
        // Get all settings grouped by prefix
        $data['general_settings'] = $this->settingModel->getByPrefix('site_');
        $data['photo_settings'] = $this->settingModel->getByPrefix('user_photo_');
        $data['default_avatar'] = $this->settingModel->getByKey('default_avatar_path');
        $data['commission_settings'] = $this->settingModel->getByPrefix('default_commission');
        $data['payment_settings'] = [
            $this->settingModel->getByKey('gcash_enabled'),
            $this->settingModel->getByKey('withdrawal_day')
        ];
        $data['order_settings'] = [
            $this->settingModel->getByKey('minimum_order_amount'),
            $this->settingModel->getByKey('delivery_fee'),
            $this->settingModel->getByKey('low_stock_threshold')
        ];
        $data['maintenance'] = $this->settingModel->getByKey('maintenance_mode');
        
        $this->template('settings/index', $data);
    }
    
    /**
     * Update general settings
     */
    public function updateGeneral() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'site_name' => $_POST['site_name'] ?? '',
                'site_email' => $_POST['site_email'] ?? '',
                'site_phone' => $_POST['site_phone'] ?? '',
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0'
            ];
            
            foreach ($settings as $key => $value) {
                $type = ($key === 'maintenance_mode') ? 'boolean' : 'string';
                $this->settingModel->updateSetting($key, $value, $type, '');
            }
            
            $this->setFlash('success', 'General settings updated successfully');
        }
        
        $this->redirect('settings?tab=general');
    }
    
    /**
     * Update photo upload settings
     */
    public function updatePhoto() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'user_photo_max_size' => ['value' => $_POST['max_size'] ?? '2097152', 'type' => 'number'],
                'user_photo_allowed_types' => ['value' => $_POST['allowed_types'] ?? 'jpeg,jpg,png,gif', 'type' => 'string'],
                'user_photo_max_width' => ['value' => $_POST['max_width'] ?? '800', 'type' => 'number'],
                'user_photo_max_height' => ['value' => $_POST['max_height'] ?? '800', 'type' => 'number']
            ];
            
            foreach ($settings as $key => $data) {
                $this->settingModel->updateSetting($key, $data['value'], $data['type'], '');
            }
            
            $this->setFlash('success', 'Photo upload settings updated successfully');
        }
        
        $this->redirect('settings?tab=photo');
    }
    
    /**
     * Update order settings
     */
    public function updateOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'minimum_order_amount' => ['value' => $_POST['minimum_order_amount'] ?? '100', 'type' => 'number'],
                'delivery_fee' => ['value' => $_POST['delivery_fee'] ?? '50', 'type' => 'number'],
                'low_stock_threshold' => ['value' => $_POST['low_stock_threshold'] ?? '10', 'type' => 'number']
            ];
            
            foreach ($settings as $key => $data) {
                $this->settingModel->updateSetting($key, $data['value'], $data['type'], '');
            }
            
            $this->setFlash('success', 'Order settings updated successfully');
        }
        
        $this->redirect('settings?tab=order');
    }
    
    /**
     * Update payment settings
     */
    public function updatePayment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'gcash_enabled' => ['value' => isset($_POST['gcash_enabled']) ? '1' : '0', 'type' => 'boolean'],
                'withdrawal_day' => ['value' => $_POST['withdrawal_day'] ?? 'Friday', 'type' => 'string'],
                'default_commission_rate' => ['value' => $_POST['commission_rate'] ?? '15', 'type' => 'number']
            ];
            
            foreach ($settings as $key => $data) {
                $this->settingModel->updateSetting($key, $data['value'], $data['type'], '');
            }
            
            $this->setFlash('success', 'Payment settings updated successfully');
        }
        
        $this->redirect('settings?tab=payment');
    }
}
