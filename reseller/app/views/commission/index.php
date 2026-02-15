<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-dollar-sign"></i> Commission History</h3>
    </div>
    <div class="card-body">
        <!-- Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <div style="background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
                <div style="font-size: 0.875rem; opacity: 0.9;">Total Earned</div>
                <div style="font-size: 1.75rem; font-weight: bold; margin-top: 0.5rem;">
                    ₱<?php echo number_format($stats['total_earned'] ?? 0, 2); ?>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
                <div style="font-size: 0.875rem; opacity: 0.9;">Pending</div>
                <div style="font-size: 1.75rem; font-weight: bold; margin-top: 0.5rem;">
                    ₱<?php echo number_format($stats['pending'] ?? 0, 2); ?>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); color: white; padding: 1.5rem; border-radius: 0.5rem;">
                <div style="font-size: 0.875rem; opacity: 0.9;">Available</div>
                <div style="font-size: 1.75rem; font-weight: bold; margin-top: 0.5rem;">
                    ₱<?php echo number_format($stats['available'] ?? 0, 2); ?>
                </div>
            </div>
        </div>

        <!-- Commission List -->
        <?php if (empty($commissions)): ?>
            <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No commission records found</p>
        <?php else: ?>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commissions as $commission): ?>
                            <tr>
                                <td><strong>#<?php echo str_pad($commission['transaction_id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><?php echo $commission['order_id'] ? '#' . str_pad($commission['order_id'], 6, '0', STR_PAD_LEFT) : 'N/A'; ?></td>
                                <td>₱<?php echo number_format($commission['amount'], 2); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $commission['transaction_type'] === 'earned' ? 'success' : 'info'; ?>">
                                        <?php echo ucfirst($commission['transaction_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?php 
                                        echo match($commission['status']) {
                                            'pending' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo ucfirst($commission['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y h:i A', strtotime($commission['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
