<!-- Add Commission Payout -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hand-holding-usd"></i> Add Commission Payout
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>commission" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Commissions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Commission Form -->
    <div class="row">
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Payout Information
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>commission/add" id="commissionForm">
                        <!-- Select Reseller -->
                        <div class="form-group">
                            <label for="reseller_id">Select Reseller <span style="color: red;">*</span></label>
                            <select class="form-control" id="reseller_id" name="reseller_id" required onchange="loadResellerInfo(this.value)">
                                <option value="">-- Choose a Reseller --</option>
                                <?php if (!empty($resellers)): ?>
                                    <?php foreach ($resellers as $reseller): ?>
                                        <option value="<?php echo $reseller['reseller_id'] ?? $reseller['id']; ?>" 
                                                data-rate="<?php echo $reseller['commission_rate']; ?>"
                                                data-name="<?php echo htmlspecialchars($reseller['full_name']); ?>"
                                                data-email="<?php echo htmlspecialchars($reseller['email']); ?>">
                                            <?php echo htmlspecialchars($reseller['full_name']); ?> 
                                            (<?php echo htmlspecialchars($reseller['email']); ?>) - 
                                            <?php echo $reseller['commission_rate']; ?>% rate
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No approved resellers available</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Reseller Info Display -->
                        <div id="resellerInfo" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid var(--primary-pink);">
                            <strong><i class="fas fa-user-tie"></i> Selected Reseller:</strong><br>
                            <span id="resellerName"></span><br>
                            <small class="text-muted"><span id="resellerEmail"></span> | Commission Rate: <span id="resellerRate"></span>%</small>
                        </div>

                        <!-- Order ID (Optional) -->
                        <div class="form-group">
                            <label for="order_id">Related Order ID (Optional)</label>
                            <input type="number" class="form-control" id="order_id" name="order_id" 
                                   placeholder="Enter order ID if this commission is for a specific order">
                            <small class="text-muted">Leave blank for manual payouts not tied to orders</small>
                        </div>

                        <!-- Commission Rate -->
                        <div class="form-group">
                            <label for="commission_rate">Commission Rate (%) <span style="color: red;">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="commission_rate" name="commission_rate" 
                                       value="10" min="0" max="100" step="0.5" required onchange="calculateCommission()">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small class="text-muted">Will auto-fill with reseller's default rate</small>
                        </div>

                        <!-- Commission Amount -->
                        <div class="form-group">
                            <label for="amount">Commission Amount <span style="color: red;">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₱</span>
                                </div>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                       step="0.01" min="0" required placeholder="0.00" onchange="recalculateSale()">
                            </div>
                        </div>

                        <!-- Sale Amount Calculator -->
                        <div style="padding: 15px; background: linear-gradient(135deg, #ff69b4 0%, #ffb6c1 100%); color: white; border-radius: 8px; margin-bottom: 20px;">
                            <strong>Sale Amount Calculator:</strong><br>
                            <div style="margin-top: 10px;">
                                <label style="display: block; margin-bottom: 5px;">Total Sale Amount:</label>
                                <input type="number" id="saleAmount" value="1000" min="0" step="100" onchange="calculateCommission()" 
                                       style="width: 150px; padding: 8px; border: none; border-radius: 4px; font-weight: bold;">
                                <button type="button" onclick="calculateCommission()" 
                                        style="padding: 8px 15px; margin-left: 10px; border: none; border-radius: 4px; background: white; color: var(--primary-pink); font-weight: bold; cursor: pointer;">
                                    Calculate
                                </button>
                            </div>
                            <div style="margin-top: 10px; font-size: 16px;">
                                <strong>Commission: </strong><span id="calculatedCommission">₱100.00</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-group">
                            <label for="payment_method">Payment Method <span style="color: red;">*</span></label>
                            <select class="form-control" id="payment_method" name="payment_method" required onchange="showPaymentDetails(this.value)">
                                <option value="">-- Select Payment Method --</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="gcash">GCash</option>
                                <option value="paymaya">PayMaya</option>
                                <option value="palawan_express">Palawan Express</option>
                                <option value="cash">Cash</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Payment Details -->
                        <div class="form-group" id="paymentDetailsGroup" style="display: none;">
                            <label for="payment_details">Payment Details</label>
                            <textarea class="form-control" id="payment_details" name="payment_details" rows="3" 
                                      placeholder="Enter payment details (account number, reference number, etc.)"></textarea>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Payout Status <span style="color: red;">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Pending - Awaiting approval</option>
                                <option value="approved">Approved - Ready for payout</option>
                                <option value="paid">Paid - Already processed</option>
                                <option value="cancelled">Cancelled - Payout cancelled</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="form-group">
                            <label for="notes">Internal Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Add any additional notes about this commission (optional)"></textarea>
                            <small class="text-muted">These notes are only visible to admins</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Add Commission
                            </button>
                            <a href="<?php echo BASE_URL; ?>commission" class="btn btn-outline btn-lg" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col col-4">
            <!-- Commission Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list"></i> Guidelines
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 20px; margin: 0; font-size: 14px;">
                        <li style="margin-bottom: 10px;">Verify reseller sales before adding commission</li>
                        <li style="margin-bottom: 10px;">Commission rate can be adjusted per reseller</li>
                        <li style="margin-bottom: 10px;">Link to order ID when applicable for tracking</li>
                        <li style="margin-bottom: 10px;">Use "Pending" status for new commissions</li>
                        <li>Always document payment details for reference</li>
                    </ul>
                </div>
            </div>

            <!-- Payment Methods Info -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-money-check-alt"></i> Payment Methods
                    </h3>
                </div>
                <div class="card-body" style="font-size: 14px;">
                    <p><strong>Bank Transfer:</strong> Fastest for large amounts</p>
                    <p><strong>GCash/PayMaya:</strong> Instant mobile payments</p>
                    <p><strong>Palawan Express:</strong> Cash pickup nationwide</p>
                    <p style="margin: 0;"><strong>Cash:</strong> For local pickups only</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Quick Stats
                    </h3>
                </div>
                <div class="card-body" style="font-size: 14px;">
                    <p><strong>Default Rate:</strong> 10%</p>
                    <p><strong>Min Payout:</strong> ₱500</p>
                    <p style="margin: 0;"><strong>Schedule:</strong> Monthly</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Load reseller information when selected
function loadResellerInfo(resellerId) {
    const selectElement = document.getElementById('reseller_id');
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    
    if (resellerId && selectedOption) {
        const rate = selectedOption.getAttribute('data-rate');
        const name = selectedOption.getAttribute('data-name');
        const email = selectedOption.getAttribute('data-email');
        
        // Update commission rate
        document.getElementById('commission_rate').value = rate;
        
        // Show reseller info
        document.getElementById('resellerName').textContent = name;
        document.getElementById('resellerEmail').textContent = email;
        document.getElementById('resellerRate').textContent = rate;
        document.getElementById('resellerInfo').style.display = 'block';
        
        // Recalculate commission
        calculateCommission();
    } else {
        document.getElementById('resellerInfo').style.display = 'none';
    }
}

// Calculate commission from sale amount
function calculateCommission() {
    const saleAmount = parseFloat(document.getElementById('saleAmount').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    const commission = (saleAmount * commissionRate) / 100;
    
    document.getElementById('calculatedCommission').textContent = '₱' + commission.toFixed(2);
    document.getElementById('amount').value = commission.toFixed(2);
}

// Recalculate sale amount from commission
function recalculateSale() {
    const commission = parseFloat(document.getElementById('amount').value) || 0;
    const commissionRate = parseFloat(document.getElementById('commission_rate').value) || 0;
    
    if (commissionRate > 0) {
        const saleAmount = (commission * 100) / commissionRate;
        document.getElementById('saleAmount').value = saleAmount.toFixed(2);
        document.getElementById('calculatedCommission').textContent = '₱' + commission.toFixed(2);
    }
}

// Show/hide payment details based on method
function showPaymentDetails(method) {
    const detailsGroup = document.getElementById('paymentDetailsGroup');
    if (method && method !== '') {
        detailsGroup.style.display = 'block';
    } else {
        detailsGroup.style.display = 'none';
    }
}

// Form validation
document.getElementById('commissionForm').addEventListener('submit', function(e) {
    const amount = parseFloat(document.getElementById('amount').value);
    const resellerId = document.getElementById('reseller_id').value;
    
    if (!resellerId) {
        e.preventDefault();
        alert('Please select a reseller.');
        return false;
    }
    
    if (amount <= 0) {
        e.preventDefault();
        alert('Commission amount must be greater than zero.');
        document.getElementById('amount').focus();
        return false;
    }
});

// Initialize
calculateCommission();
</script>
