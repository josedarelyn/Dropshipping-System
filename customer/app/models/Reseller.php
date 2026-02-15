<?php
/**
 * Reseller Model - For referral tracking
 */

class Reseller extends Model {
    protected $table = 'reseller_profiles';
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE reseller_id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getByReferralCode($code) {
        $sql = "SELECT * FROM {$this->table} WHERE referral_code = :code AND status = 'approved' LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['code' => $code]);
        return $stmt->fetch();
    }
}
