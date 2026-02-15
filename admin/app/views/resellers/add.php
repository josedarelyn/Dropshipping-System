<!-- Add Reseller Application -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-tie"></i> Add Reseller Application
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>reseller" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Resellers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reseller Form -->
    <div class="row">
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Reseller Information
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>reseller/add" id="resellerForm">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="full_name">Full Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required 
                                   placeholder="Enter full name" value="<?php echo isset($reseller['full_name']) ? htmlspecialchars($reseller['full_name']) : ''; ?>">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email Address <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="reseller@example.com" value="<?php echo isset($reseller['email']) ? htmlspecialchars($reseller['email']) : ''; ?>">
                            <small class="text-muted">Will be used for login and commission notifications</small>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone">Phone Number <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" required 
                                   placeholder="+63 XXX XXX XXXX" value="<?php echo isset($reseller['phone']) ? htmlspecialchars($reseller['phone']) : ''; ?>">
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Complete Address <span style="color: red;">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required 
                                      placeholder="Street, Barangay, City, Province, Zip Code"><?php echo isset($reseller['address']) ? htmlspecialchars($reseller['address']) : ''; ?></textarea>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">Password <span style="color: red;">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required 
                                       placeholder="Enter password" minlength="6">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="passwordIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password <span style="color: red;">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required 
                                   placeholder="Re-enter password" minlength="6">
                        </div>

                        <!-- Commission Rate -->
                        <div class="form-group">
                            <label for="commission_rate">Commission Rate (%) <span style="color: red;">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                       value="10" min="0" max="100" step="0.5" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small class="text-muted">Default: 10%. Percentage of sales earned as commission.</small>
                        </div>

                        <!-- Commission Calculator -->
                        <div style="padding: 15px; background: linear-gradient(135deg, #ff69b4 0%, #ffb6c1 100%); color: white; border-radius: 8px; margin-bottom: 20px;">
                            <strong>Commission Example:</strong><br>
                            Sale Amount: <input type="number" id="exampleSale" value="1000" min="0" step="100" onchange="calculateCommission()" 
                                   style="width: 120px; padding: 5px; border: none; border-radius: 4px; margin: 5px 0;">
                            <br><strong>Commission Earned: </strong><span id="commissionAmount">₱100.00</span>
                        </div>

                        <!-- Application Status -->
                        <div class="form-group">
                            <label for="status">Application Status <span style="color: red;">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending - Awaiting approval</option>
                                <option value="approved">Approved - Active reseller</option>
                                <option value="rejected">Rejected - Application denied</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Internal Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Add any internal notes about this reseller (optional)"><?php echo isset($reseller['notes']) ? htmlspecialchars($reseller['notes']) : ''; ?></textarea>
                            <small class="text-muted">These notes are only visible to admins</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Add Reseller
                            </button>
                            <a href="<?php echo BASE_URL; ?>reseller" class="btn btn-outline btn-lg" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col col-4">
            <!-- Reseller Benefits -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-star"></i> Reseller Benefits
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 20px; margin: 0;">
                        <li style="margin-bottom: 10px;"><strong>Earn Commission:</strong> Get paid for every sale</li>
                        <li style="margin-bottom: 10px;"><strong>Marketing Tools:</strong> Access promotional materials</li>
                        <li style="margin-bottom: 10px;"><strong>Flexible Schedule:</strong> Work at your own pace</li>
                        <li style="margin-bottom: 10px;"><strong>No Upfront Cost:</strong> Free to join</li>
                        <li><strong>Support:</strong> Dedicated reseller support team</li>
                    </ul>
                </div>
            </div>

            <!-- Commission Info -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hand-holding-usd"></i> Commission Structure
                    </h3>
                </div>
                <div class="card-body">
                    <p><strong>Standard Rate:</strong> 10%</p>
                    <p><strong>High Performers:</strong> Up to 15%</p>
                    <p><strong>Payout Schedule:</strong> Monthly</p>
                    <p style="margin: 0;"><strong>Minimum Payout:</strong> ₱500</p>
                </div>
            </div>

            <!-- Requirements -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check"></i> Requirements
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 20px; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 8px;">Valid email and phone number</li>
                        <li style="margin-bottom: 8px;">Complete shipping address</li>
                        <li style="margin-bottom: 8px;">Active social media presence (recommended)</li>
                        <li>Commitment to customer service</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('passwordIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

// Calculate commission example
function calculateCommission() {
    const saleAmount = parseFloat(document.getElementById('exampleSale').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    const commission = (saleAmount * commissionRate) / 100;
    
    document.getElementById('commissionAmount').textContent = '₱' + commission.toFixed(2);
}

// Update commission calculation when rate changes
document.getElementById('commission_rate').addEventListener('input', calculateCommission);

// Form validation
document.getElementById('resellerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match. Please try again.');
        document.getElementById('confirm_password').focus();
        return false;
    }
    
    const commissionRate = parseFloat(document.getElementById('commission_rate').value);
    if (commissionRate < 0 || commissionRate > 100) {
        e.preventDefault();
        alert('Commission rate must be between 0% and 100%.');
        document.getElementById('commission_rate').focus();
        return false;
    }
});

// Email validation
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        this.focus();
    }
});

// Initialize commission calculation
calculateCommission();
</script>
