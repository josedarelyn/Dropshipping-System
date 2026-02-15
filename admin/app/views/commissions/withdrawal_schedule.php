<!-- Withdrawal Schedule Settings -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog"></i> Withdrawal Schedule Settings
                    </h3>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline" onclick="resetSettings()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('settingsForm').submit()">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="row">
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt"></i> Schedule Configuration
                    </h3>
                </div>
                <div class="card-body">
                    <form id="settingsForm" method="POST" action="<?php echo BASE_URL; ?>commission/withdrawalSchedule">
                        <!-- Withdrawal Frequency -->
                        <div class="form-group">
                            <label for="withdrawal_frequency">
                                <i class="fas fa-clock"></i> Withdrawal Frequency
                            </label>
                            <select class="form-control" id="withdrawal_frequency" name="withdrawal_frequency" required>
                                <option value="weekly">Weekly</option>
                                <option value="bi-weekly">Bi-Weekly (Every 2 Weeks)</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="custom">Custom Schedule</option>
                            </select>
                            <small class="form-text text-muted">How often resellers can request commission withdrawals</small>
                        </div>

                        <!-- Minimum Withdrawal Amount -->
                        <div class="form-group">
                            <label for="min_withdrawal">
                                <i class="fas fa-peso-sign"></i> Minimum Withdrawal Amount
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input type="number" class="form-control" id="min_withdrawal" name="min_withdrawal" 
                                       value="500" min="0" step="0.01" required>
                            </div>
                            <small class="form-text text-muted">Resellers must have at least this amount to request withdrawal</small>
                        </div>

                        <!-- Maximum Withdrawal Amount -->
                        <div class="form-group">
                            <label for="max_withdrawal">
                                <i class="fas fa-hand-holding-usd"></i> Maximum Withdrawal Amount (Optional)
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input type="number" class="form-control" id="max_withdrawal" name="max_withdrawal" 
                                       value="50000" min="0" step="0.01" placeholder="Leave blank for no limit">
                            </div>
                            <small class="form-text text-muted">Maximum amount per withdrawal request (optional)</small>
                        </div>

                        <!-- Processing Days -->
                        <div class="form-group">
                            <label for="processing_days">
                                <i class="fas fa-hourglass-half"></i> Processing Time (Business Days)
                            </label>
                            <input type="number" class="form-control" id="processing_days" name="processing_days" 
                                   value="3" min="1" max="30" required>
                            <small class="form-text text-muted">Number of business days to process withdrawal requests</small>
                        </div>

                        <!-- Withdrawal Day (for monthly) -->
                        <div class="form-group">
                            <label for="withdrawal_day">
                                <i class="fas fa-calendar-day"></i> Monthly Withdrawal Day
                            </label>
                            <select class="form-control" id="withdrawal_day" name="withdrawal_day">
                                <option value="1">1st of the month</option>
                                <option value="5">5th of the month</option>
                                <option value="10">10th of the month</option>
                                <option value="15" selected>15th of the month</option>
                                <option value="20">20th of the month</option>
                                <option value="25">25th of the month</option>
                                <option value="last">Last day of the month</option>
                            </select>
                            <small class="form-text text-muted">Specific day when withdrawals are processed (for monthly frequency)</small>
                        </div>

                        <!-- Auto-Approval Settings -->
                        <div class="form-group">
                            <label><i class="fas fa-check-circle"></i> Auto-Approval Settings</label>
                            <div style="margin-top: 8px;">
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="auto_approve" name="auto_approve" style="margin-right: 8px;">
                                    Enable auto-approval for amounts below threshold
                                </label>
                            </div>
                            <small class="form-text text-muted">Automatically approve withdrawals below the specified amount</small>
                        </div>

                        <div class="form-group" id="auto_approve_threshold_group" style="display: none;">
                            <label for="auto_approve_threshold">Auto-Approval Threshold</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input type="number" class="form-control" id="auto_approve_threshold" 
                                       name="auto_approve_threshold" value="5000" min="0" step="0.01">
                            </div>
                        </div>

                        <!-- OTP Verification -->
                        <div class="form-group">
                            <label><i class="fas fa-shield-alt"></i> Security Settings</label>
                            <div style="margin-top: 8px;">
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="require_otp" name="require_otp" checked style="margin-right: 8px;">
                                    Require OTP verification for withdrawals
                                </label>
                            </div>
                            <small class="form-text text-muted">Add extra security layer with OTP confirmation</small>
                        </div>

                        <!-- Payment Methods -->
                        <div class="form-group">
                            <label><i class="fas fa-money-check"></i> Allowed Payment Methods</label>
                            <div style="margin-top: 8px;">
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="method_bank" name="payment_methods[]" value="bank_transfer" checked style="margin-right: 8px;">
                                    Bank Transfer
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="method_gcash" name="payment_methods[]" value="gcash" checked style="margin-right: 8px;">
                                    GCash
                                </label>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="method_paymaya" name="payment_methods[]" value="paymaya" style="margin-right: 8px;">
                                    PayMaya
                                </label>
                                <label style="display: block;">
                                    <input type="checkbox" id="method_palawan" name="payment_methods[]" value="palawan" style="margin-right: 8px;">
                                    Palawan Express
                                </label>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="form-group">
                            <label><i class="fas fa-bell"></i> Notification Settings</label>
                            <div style="margin-top: 8px;">
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="notify_admin" name="notify_admin" checked style="margin-right: 8px;">
                                    Notify admin on new withdrawal requests
                                </label>
                                <label style="display: block;">
                                    <input type="checkbox" id="notify_reseller" name="notify_reseller" checked style="margin-right: 8px;">
                                    Notify reseller on status updates
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-outline btn-lg" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col col-4">
            <!-- Current Settings Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Current Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Frequency:</strong>
                        <p class="mb-1">Monthly (15th of month)</p>
                    </div>
                    <div class="mb-3">
                        <strong>Min Amount:</strong>
                        <p class="mb-1">₱500.00</p>
                    </div>
                    <div class="mb-3">
                        <strong>Processing Time:</strong>
                        <p class="mb-1">3 business days</p>
                    </div>
                    <div class="mb-3">
                        <strong>OTP Required:</strong>
                        <p class="mb-1"><span class="badge badge-success">Yes</span></p>
                    </div>
                    <div class="mb-0">
                        <strong>Last Updated:</strong>
                        <p class="mb-0"><?php echo date('M d, Y'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-question-circle"></i> Need Help?
                    </h3>
                </div>
                <div class="card-body">
                    <p class="small"><strong>Withdrawal Frequency:</strong> Determines how often resellers can request their commissions.</p>
                    <p class="small"><strong>Minimum Amount:</strong> Prevents small withdrawal requests that may incur processing fees.</p>
                    <p class="small"><strong>Auto-Approval:</strong> Speeds up the process for trusted resellers with small amounts.</p>
                    <p class="small mb-0"><strong>OTP Verification:</strong> Recommended for security and fraud prevention.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ee82ee 0%, #9370db 100%);">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Processed This Month</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9370db 0%, #ff69b4 100%);">
                    <i class="fas fa-peso-sign"></i>
                </div>
                <div class="stat-value">₱0</div>
                <div class="stat-label">Total Paid Out</div>
            </div>
        </div>
        <div class="col col-3">
            <div class="card stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ffb6c1 0%, #db7093 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Active Resellers</div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Reset settings to default
function resetSettings() {
    if (confirm('Are you sure you want to reset all settings to default values?')) {
        document.getElementById('settingsForm').reset();
        document.getElementById('auto_approve_threshold_group').style.display = 'none';
    }
}

// Toggle auto-approval threshold field
document.getElementById('auto_approve').addEventListener('change', function() {
    const thresholdGroup = document.getElementById('auto_approve_threshold_group');
    thresholdGroup.style.display = this.checked ? 'block' : 'none';
});

// Validate form before submission
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    const minWithdrawal = parseFloat(document.getElementById('min_withdrawal').value);
    const maxWithdrawal = document.getElementById('max_withdrawal').value;
    
    if (maxWithdrawal && parseFloat(maxWithdrawal) < minWithdrawal) {
        e.preventDefault();
        alert('Maximum withdrawal amount cannot be less than minimum amount');
        return false;
    }
    
    return true;
});
</script>
