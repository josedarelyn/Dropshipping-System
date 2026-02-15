<style>
    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
        margin-top: 30px;
    }
    
    .checkout-section {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--dark-gray);
    }
    
    .form-group input[type="text"],
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-pink);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .delivery-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .delivery-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .delivery-option:hover {
        border-color: var(--primary-pink);
        background: #fff5f8;
    }
    
    .delivery-option input[type="radio"] {
        margin-right: 15px;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .delivery-option.selected {
        border-color: var(--primary-pink);
        background: #fff5f8;
    }
    
    .delivery-info {
        flex: 1;
    }
    
    .delivery-name {
        font-weight: 600;
        color: var(--dark-gray);
        margin-bottom: 5px;
    }
    
    .delivery-desc {
        font-size: 13px;
        color: var(--gray);
    }
    
    .delivery-fee {
        font-weight: bold;
        color: var(--primary-pink);
        font-size: 16px;
    }
    
    .payment-options {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .payment-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .payment-option:hover {
        border-color: var(--primary-pink);
        background: #fff5f8;
    }
    
    .payment-option input[type="radio"] {
        margin-right: 15px;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .payment-option.selected {
        border-color: var(--primary-pink);
        background: #fff5f8;
    }
    
    .payment-info {
        flex: 1;
    }
    
    .payment-name {
        font-weight: 600;
        color: var(--dark-gray);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .cart-summary-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }
    
    .cart-summary-item:last-child {
        border-bottom: none;
    }
    
    .cart-summary-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        background: #f0f0f0;
    }
    
    .cart-summary-details {
        flex: 1;
    }
    
    .cart-summary-name {
        font-weight: 500;
        font-size: 14px;
        color: var(--dark-gray);
        margin-bottom: 5px;
    }
    
    .cart-summary-qty {
        font-size: 13px;
        color: var(--gray);
    }
    
    .cart-summary-price {
        font-weight: 600;
        color: var(--primary-pink);
    }
    
    .order-summary {
        background: var(--light-gray);
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 15px;
    }
    
    .summary-total {
        font-size: 22px;
        font-weight: bold;
        color: var(--primary-pink);
        border-top: 2px solid #ddd;
        padding-top: 15px;
        margin-top: 10px;
    }
    
    .place-order-btn {
        width: 100%;
        padding: 18px;
        background: var(--primary-pink);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 20px;
    }
    
    .place-order-btn:hover {
        background: var(--primary-pink-dark);
    }
    
    .place-order-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    @media (max-width: 968px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<h1><i class="fas fa-credit-card"></i> Checkout</h1>

<?php if (empty($cartItems)): ?>
    <div class="empty-state">
        <i class="fas fa-shopping-cart"></i>
        <h3>Your cart is empty</h3>
        <p>Add items to your cart before checking out</p>
        <a href="<?php echo BASE_URL; ?>shop" class="btn" style="margin-top: 20px;">
            <i class="fas fa-shopping-bag"></i> Start Shopping
        </a>
    </div>
<?php else: ?>
    <form method="POST" action="<?php echo BASE_URL; ?>checkout/process" id="checkoutForm" enctype="multipart/form-data">
        <div class="checkout-container">
            <!-- Left Column -->
            <div>
                <!-- Delivery Type Section -->
                <div class="checkout-section">
                    <h2 class="section-title"><i class="fas fa-truck"></i> Delivery Method</h2>
                    <div class="delivery-options">
                        <label class="delivery-option" onclick="selectDelivery(this, 'door_to_door', 50)">
                            <input type="radio" name="delivery_type" value="door_to_door" required>
                            <div class="delivery-info">
                                <div class="delivery-name">Door-to-Door Delivery</div>
                                <div class="delivery-desc">Direct delivery to your address (3-5 business days)</div>
                            </div>
                            <div class="delivery-fee">₱50.00</div>
                        </label>
                        
                        <label class="delivery-option" onclick="selectDelivery(this, 'courier', 100)">
                            <input type="radio" name="delivery_type" value="courier" required>
                            <div class="delivery-info">
                                <div class="delivery-name">Courier Service</div>
                                <div class="delivery-desc">Standard courier delivery (5-7 business days)</div>
                            </div>
                            <div class="delivery-fee">₱100.00</div>
                        </label>
                        
                        <label class="delivery-option" onclick="selectDelivery(this, 'pickup', 0)">
                            <input type="radio" name="delivery_type" value="pickup" required>
                            <div class="delivery-info">
                                <div class="delivery-name">Store Pickup</div>
                                <div class="delivery-desc">Pick up from our store (Available immediately)</div>
                            </div>
                            <div class="delivery-fee">FREE</div>
                        </label>
                    </div>
                </div>
                
                <!-- Delivery Address Section -->
                <div class="checkout-section" style="margin-top: 20px;">
                    <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Delivery Address</h2>
                    <div class="form-group">
                        <label for="delivery_address">Complete Address *</label>
                        <textarea name="delivery_address" id="delivery_address" required placeholder="Enter your complete delivery address including street, barangay, city, and province"><?php 
                            if (!empty($defaultAddress)) {
                                $addr = $defaultAddress['full_address'];
                                if (!empty($defaultAddress['city'])) $addr .= ', ' . $defaultAddress['city'];
                                if (!empty($defaultAddress['province'])) $addr .= ', ' . $defaultAddress['province'];
                                if (!empty($defaultAddress['postal_code'])) $addr .= ' ' . $defaultAddress['postal_code'];
                                echo htmlspecialchars($addr);
                            }
                        ?></textarea>
                    </div>
                </div>
                
                <!-- Payment Method Section -->
                <div class="checkout-section" style="margin-top: 20px;">
                    <h2 class="section-title"><i class="fas fa-wallet"></i> Payment Method</h2>
                    <div class="payment-options">
                        <label class="payment-option" onclick="selectPayment(this, 'gcash')">
                            <input type="radio" name="payment_method" value="gcash" required>
                            <div class="payment-info">
                                <div class="payment-name">
                                    <i class="fas fa-mobile-alt" style="color: #007DFF;"></i>
                                    GCash
                                </div>
                                <div style="font-size:12px; color:#888; margin-top:4px;">Pay via GCash mobile wallet</div>
                            </div>
                        </label>
                        
                        <label class="payment-option" onclick="selectPayment(this, 'cod')">
                            <input type="radio" name="payment_method" value="cod" required>
                            <div class="payment-info">
                                <div class="payment-name">
                                    <i class="fas fa-money-bill-wave" style="color: #4caf50;"></i>
                                    Cash on Delivery
                                </div>
                                <div style="font-size:12px; color:#888; margin-top:4px;">Pay when you receive the product</div>
                            </div>
                        </label>
                        
                        <label class="payment-option" onclick="selectPayment(this, 'bank_transfer')">
                            <input type="radio" name="payment_method" value="bank_transfer" required>
                            <div class="payment-info">
                                <div class="payment-name">
                                    <i class="fas fa-university" style="color: #ff9800;"></i>
                                    Bank Transfer
                                </div>
                                <div style="font-size:12px; color:#888; margin-top:4px;">Direct bank deposit/transfer</div>
                            </div>
                        </label>
                    </div>

                    <!-- GCash Payment Details (shown when GCash is selected) -->
                    <div id="gcashPaymentSection" style="display:none; margin-top:20px; background:linear-gradient(135deg, #007DFF10, #007DFF05); border:2px solid #007DFF40; border-radius:12px; padding:20px;">
                        <div style="text-align:center; margin-bottom:15px;">
                            <div style="width:50px; height:50px; background:#007DFF; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:10px;">
                                <i class="fas fa-mobile-alt" style="font-size:24px; color:white;"></i>
                            </div>
                            <h3 style="color:#007DFF; margin:0; font-size:16px;">Send Payment via GCash</h3>
                        </div>
                        
                        <div style="background:white; border-radius:10px; padding:15px; margin-bottom:15px;">
                            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eee;">
                                <span style="color:#666; font-size:13px;">Account Name:</span>
                                <span style="font-weight:600; color:#333;"><?php 
                                    $db = Database::getInstance()->getConnection();
                                    $stmt = $db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'gcash_name'");
                                    $stmt->execute();
                                    $gcashName = $stmt->fetchColumn() ?: 'Dhendhen Beauty Products';
                                    echo htmlspecialchars($gcashName);
                                ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #eee;">
                                <span style="color:#666; font-size:13px;">GCash Number:</span>
                                <span style="font-weight:600; color:#007DFF; font-size:16px;" id="gcash-display-number"><?php 
                                    $stmt = $db->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'gcash_number'");
                                    $stmt->execute();
                                    $gcashNum = $stmt->fetchColumn() ?: '09123456789';
                                    echo htmlspecialchars($gcashNum);
                                ?></span>
                            </div>
                            <div style="display:flex; justify-content:space-between; padding:8px 0;">
                                <span style="color:#666; font-size:13px;">Amount to Send:</span>
                                <span style="font-weight:700; color:#007DFF; font-size:18px;" id="gcash-amount">₱<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                        </div>
                        
                        <div style="background:#fff3cd; border-radius:8px; padding:10px 14px; margin-bottom:15px; font-size:12px; color:#856404;">
                            <i class="fas fa-info-circle"></i> Send the exact amount to the GCash number above, then fill in your details below.
                        </div>
                        
                        <div class="form-group" style="margin-bottom:12px;">
                            <label style="font-size:13px; font-weight:600; color:#333;">GCash Reference Number *</label>
                            <input type="text" name="gcash_reference" id="gcash_reference" placeholder="e.g., 1234 5678 9012" 
                                   style="width:100%; padding:10px 14px; border:2px solid #ddd; border-radius:8px; font-size:14px; font-family:inherit; box-sizing:border-box;"
                                   maxlength="50">
                        </div>
                        
                        <div class="form-group" style="margin-bottom:12px;">
                            <label style="font-size:13px; font-weight:600; color:#333;">GCash Number Used *</label>
                            <input type="text" name="gcash_sender_number" id="gcash_sender_number" placeholder="e.g., 09171234567" 
                                   style="width:100%; padding:10px 14px; border:2px solid #ddd; border-radius:8px; font-size:14px; font-family:inherit; box-sizing:border-box;"
                                   maxlength="20">
                        </div>
                        
                        <div class="form-group" style="margin-bottom:0;">
                            <label style="font-size:13px; font-weight:600; color:#333;">Upload Proof of Payment (Screenshot) *</label>
                            <div id="proofUploadArea" style="border:2px dashed #007DFF40; border-radius:10px; padding:20px; text-align:center; cursor:pointer; transition:all 0.3s; background:white;" 
                                 onclick="document.getElementById('proof_of_payment').click()">
                                <div id="proofPreview" style="display:none; margin-bottom:10px;">
                                    <img id="proofImage" src="" style="max-width:200px; max-height:200px; border-radius:8px; border:2px solid #007DFF;">
                                </div>
                                <div id="proofPlaceholder">
                                    <i class="fas fa-cloud-upload-alt" style="font-size:30px; color:#007DFF; margin-bottom:8px; display:block;"></i>
                                    <span style="color:#666; font-size:13px;">Click to upload screenshot</span><br>
                                    <span style="color:#999; font-size:11px;">JPG, PNG (Max 5MB)</span>
                                </div>
                                <div id="proofFileName" style="display:none; color:#007DFF; font-weight:600; font-size:13px; margin-top:8px;"></div>
                            </div>
                            <input type="file" name="proof_of_payment" id="proof_of_payment" accept="image/jpeg,image/png,image/jpg" style="display:none;" onchange="previewProof(this)">
                        </div>
                    </div>
                </div>
                
                <!-- Order Notes Section -->
                <div class="checkout-section" style="margin-top: 20px;">
                    <h2 class="section-title"><i class="fas fa-comment"></i> Order Notes (Optional)</h2>
                    <div class="form-group">
                        <label for="order_notes">Additional instructions for your order</label>
                        <textarea name="order_notes" id="order_notes" placeholder="e.g., Special delivery instructions, gift message, etc."></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Order Summary -->
            <div>
                <div class="checkout-section" style="position: sticky; top: 100px;">
                    <h2 class="section-title"><i class="fas fa-receipt"></i> Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="cart-summary-item">
                                <?php if ($item['product_image']): ?>
                                    <img src="<?php echo BASE_URL . '../admin/' . $item['product_image']; ?>" 
                                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                                         class="cart-summary-image">
                                <?php else: ?>
                                    <div class="cart-summary-image" style="display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="color: #ccc;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="cart-summary-details">
                                    <div class="cart-summary-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                    <div class="cart-summary-qty">Qty: <?php echo $item['quantity']; ?></div>
                                </div>
                                <div class="cart-summary-price">₱<?php echo number_format($item['item_total'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Order Summary Totals -->
                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span style="font-weight: 600;" id="subtotal-display">₱<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Fee:</span>
                            <span style="font-weight: 600;" id="delivery-fee-display">₱0.00</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span>Total:</span>
                            <span id="total-display">₱<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                    </div>
                    
                    <input type="hidden" name="subtotal" value="<?php echo $subtotal; ?>">
                    <input type="hidden" name="delivery_fee" id="delivery_fee" value="0">
                    <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $subtotal; ?>">
                    
                    <button type="button" class="place-order-btn" onclick="showConfirmModal()">
                        <i class="fas fa-check-circle"></i> Place Order
                    </button>
                    
                    <a href="<?php echo BASE_URL; ?>cart" style="display: block; text-align: center; margin-top: 15px; color: var(--gray); text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </form>

<!-- Order Confirmation Modal -->
<div id="confirmModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; max-width:500px; width:90%; margin:auto; padding:35px; box-shadow:0 20px 60px rgba(0,0,0,0.3); animation:modalSlideIn 0.3s ease-out; position:relative; top:50%; transform:translateY(-50%); max-height:90vh; overflow-y:auto;">
        <div style="text-align:center; margin-bottom:25px;">
            <div style="width:70px; height:70px; background:linear-gradient(135deg, #ff69b4, #ee82ee); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 15px;">
                <i class="fas fa-shopping-bag" style="font-size:30px; color:white;"></i>
            </div>
            <h2 style="font-size:22px; color:#333; margin-bottom:5px;">Confirm Your Order</h2>
            <p style="color:#888; font-size:14px;">Please review your order details before placing</p>
        </div>

        <div style="background:#f8f9fa; border-radius:10px; padding:18px; margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:14px; color:#555;">
                <span>Items:</span>
                <span style="font-weight:600;" id="confirm-items"><?php echo count($cartItems); ?> product(s)</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:14px; color:#555;">
                <span>Delivery:</span>
                <span style="font-weight:600;" id="confirm-delivery">--</span>
            </div>
            <div style="display:flex; justify-content:space-between; padding:8px 0; font-size:14px; color:#555;">
                <span>Payment:</span>
                <span style="font-weight:600;" id="confirm-payment">--</span>
            </div>
            <!-- GCash details in confirm modal -->
            <div id="confirm-gcash-details" style="display:none; padding:10px 0 4px; border-top:1px dashed #ddd; margin-top:4px;">
                <div style="display:flex; justify-content:space-between; padding:4px 0; font-size:13px; color:#555;">
                    <span>Ref. Number:</span>
                    <span style="font-weight:600; color:#007DFF;" id="confirm-gcash-ref">--</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:4px 0; font-size:13px; color:#555;">
                    <span>Sender:</span>
                    <span style="font-weight:600;" id="confirm-gcash-sender">--</span>
                </div>
                <div style="display:flex; justify-content:space-between; padding:4px 0; font-size:13px; color:#555;">
                    <span>Proof:</span>
                    <span style="font-weight:600; color:#28a745;" id="confirm-gcash-proof"><i class="fas fa-check-circle"></i> Uploaded</span>
                </div>
            </div>
            <div style="display:flex; justify-content:space-between; padding:12px 0 0; font-size:18px; font-weight:700; color:var(--primary-pink); border-top:2px solid #ddd; margin-top:8px;">
                <span>Total:</span>
                <span id="confirm-total">₱<?php echo number_format($subtotal, 2); ?></span>
            </div>
        </div>

        <div style="display:flex; gap:12px;">
            <button type="button" onclick="closeConfirmModal()" style="flex:1; padding:14px; border:2px solid #ddd; background:white; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; font-family:inherit; color:#666; transition:all 0.2s;">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>
            <button type="button" onclick="confirmAndSubmit()" id="btnConfirmPurchase" style="flex:1; padding:14px; border:none; background:linear-gradient(135deg, #ff69b4, #ee82ee); color:white; border-radius:10px; font-size:15px; font-weight:600; cursor:pointer; font-family:inherit; transition:all 0.2s;">
                <i class="fas fa-check"></i> Confirm Purchase
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes modalSlideIn {
        from { opacity:0; transform:translateY(-40%); }
        to { opacity:1; transform:translateY(-50%); }
    }
    #proofUploadArea:hover {
        border-color: #007DFF;
        background: #007DFF08 !important;
    }
</style>

<script>
    const subtotal = <?php echo $subtotal; ?>;
    let deliveryFee = 0;
    let selectedPayment = '';
    
    function selectDelivery(element, type, fee) {
        document.querySelectorAll('.delivery-option').forEach(opt => opt.classList.remove('selected'));
        element.classList.add('selected');
        deliveryFee = fee;
        updateTotal();
    }
    
    function selectPayment(element, method) {
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
        element.classList.add('selected');
        selectedPayment = method;
        
        // Show/hide GCash payment section
        const gcashSection = document.getElementById('gcashPaymentSection');
        if (method === 'gcash') {
            gcashSection.style.display = 'block';
            updateGcashAmount();
        } else {
            gcashSection.style.display = 'none';
        }
    }
    
    function updateTotal() {
        const total = subtotal + deliveryFee;
        document.getElementById('delivery-fee-display').textContent = '₱' + deliveryFee.toFixed(2);
        document.getElementById('total-display').textContent = '₱' + total.toFixed(2);
        document.getElementById('delivery_fee').value = deliveryFee;
        document.getElementById('total_amount').value = total;
        updateGcashAmount();
    }
    
    function updateGcashAmount() {
        const total = subtotal + deliveryFee;
        const gcashAmountEl = document.getElementById('gcash-amount');
        if (gcashAmountEl) {
            gcashAmountEl.textContent = '₱' + total.toFixed(2);
        }
    }
    
    function previewProof(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                input.value = '';
                return;
            }
            
            // Validate file type
            if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
                alert('Only JPG and PNG files are allowed');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('proofImage').src = e.target.result;
                document.getElementById('proofPreview').style.display = 'block';
                document.getElementById('proofPlaceholder').style.display = 'none';
                document.getElementById('proofFileName').style.display = 'block';
                document.getElementById('proofFileName').textContent = file.name;
            };
            reader.readAsDataURL(file);
        }
    }
    
    function showConfirmModal() {
        const deliveryType = document.querySelector('input[name="delivery_type"]:checked');
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        const deliveryAddress = document.getElementById('delivery_address').value.trim();
        
        if (!deliveryType) { alert('Please select a delivery method'); return; }
        if (!paymentMethod) { alert('Please select a payment method'); return; }
        if (!deliveryAddress) { alert('Please enter your delivery address'); return; }
        
        // GCash validation
        if (paymentMethod.value === 'gcash') {
            const gcashRef = document.getElementById('gcash_reference').value.trim();
            const gcashSender = document.getElementById('gcash_sender_number').value.trim();
            const proofFile = document.getElementById('proof_of_payment').files[0];
            
            if (!gcashRef) { alert('Please enter your GCash reference number'); document.getElementById('gcash_reference').focus(); return; }
            if (!gcashSender) { alert('Please enter the GCash number you used to send payment'); document.getElementById('gcash_sender_number').focus(); return; }
            if (!proofFile) { alert('Please upload your proof of payment screenshot'); return; }
            
            // Show GCash details in confirm modal
            document.getElementById('confirm-gcash-details').style.display = 'block';
            document.getElementById('confirm-gcash-ref').textContent = gcashRef;
            document.getElementById('confirm-gcash-sender').textContent = gcashSender;
        } else {
            document.getElementById('confirm-gcash-details').style.display = 'none';
        }
        
        const deliveryLabels = {
            'door_to_door': 'Door-to-Door (₱50.00)',
            'courier': 'Courier Service (₱100.00)',
            'pickup': 'Store Pickup (FREE)'
        };
        const paymentLabels = {
            'gcash': 'GCash',
            'cod': 'Cash on Delivery',
            'bank_transfer': 'Bank Transfer'
        };
        
        document.getElementById('confirm-delivery').textContent = deliveryLabels[deliveryType.value] || deliveryType.value;
        document.getElementById('confirm-payment').textContent = paymentLabels[paymentMethod.value] || paymentMethod.value;
        document.getElementById('confirm-total').textContent = '₱' + (subtotal + deliveryFee).toFixed(2);
        
        document.getElementById('confirmModal').style.display = 'flex';
    }
    
    function closeConfirmModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }
    
    function confirmAndSubmit() {
        const btn = document.getElementById('btnConfirmPurchase');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        document.getElementById('confirmModal').style.display = 'none';
        document.getElementById('checkoutForm').submit();
    }
    
    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });
</script>

<?php endif; ?>
