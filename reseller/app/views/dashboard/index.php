<?php if (isset($_SESSION['reseller_status']) && $_SESSION['reseller_status'] !== 'approved'): ?>
    <!-- Pending/Rejected Account Message -->
    <div class="card">
        <div class="card-body" style="text-align: center; padding: 3rem;">
            <?php if ($_SESSION['reseller_status'] === 'pending'): ?>
                <i class="fas fa-hourglass-half" style="font-size: 4rem; color: var(--warning); margin-bottom: 1rem;"></i>
                <h2 style="color: var(--gray-800); margin-bottom: 1rem;">Account Pending Approval</h2>
                <p style="color: var(--gray-600); font-size: 1.125rem;">
                    Your reseller account is currently under review. You will be notified via email once your account has been approved by our team.
                </p>
                <p style="color: var(--gray-500); font-size: 0.875rem; margin-top: 1rem;">
                    This usually takes 1-2 business days.
                </p>
            <?php else: ?>
                <i class="fas fa-times-circle" style="font-size: 4rem; color: var(--danger); margin-bottom: 1rem;"></i>
                <h2 style="color: var(--gray-800); margin-bottom: 1rem;">Account Rejected</h2>
                <p style="color: var(--gray-600); font-size: 1.125rem;">
                    Unfortunately, your reseller application has been rejected. Please contact support for more information.
                </p>
                <?php if ($reseller && !empty($reseller['rejection_reason'])): ?>
                    <div style="background: var(--gray-100); padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;">
                        <strong>Reason:</strong> <?php echo htmlspecialchars($reseller['rejection_reason']); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pink">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
            <div class="stat-label">Total Orders</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon violet">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-value">₱<?php echo number_format($stats['total_sales'], 2); ?></div>
            <div class="stat-label">Total Sales</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-percent"></i>
            </div>
            <div class="stat-value">₱<?php echo number_format($stats['pending_commission'], 2); ?></div>
            <div class="stat-label">Pending Commission</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pink">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-value">₱<?php echo number_format($stats['wallet_balance'], 2); ?></div>
            <div class="stat-label">E-Wallet Balance</div>
        </div>
    </div>

    <!-- Commission Info -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> Commission Information</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.25rem;">Commission Rate</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--primary-pink);">
                        <?php echo number_format($reseller['commission_rate'] ?? 15, 2); ?>%
                    </div>
                </div>
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.25rem;">Total Earned</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--success);">
                        ₱<?php echo number_format($stats['total_earned'], 2); ?>
                    </div>
                </div>
                <div>
                    <div style="color: var(--gray-600); font-size: 0.875rem; margin-bottom: 0.25rem;">Total Withdrawn</div>
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--info);">
                        ₱<?php echo number_format($stats['total_withdrawn'], 2); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-clock"></i> Recent Orders</h3>
        </div>
        <div class="card-body">
            <?php if (empty($recentOrders)): ?>
                <p style="text-align: center; color: var(--gray-500); padding: 2rem;">
                    <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.3;"></i>
                    No orders yet
                </p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $order['order_status']; ?>">
                                        <?php echo ucfirst($order['order_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $order['payment_status']; ?>">
                                        <?php echo ucfirst($order['payment_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
