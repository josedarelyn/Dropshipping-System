<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-wallet"></i> E-Wallet Balance</h3>
            </div>
            <div class="card-body">
                <div style="text-align: center; padding: 2rem;">
                    <div style="font-size: 3rem; font-weight: bold; color: var(--primary-pink); margin-bottom: 1rem;">
                        ₱<?php echo number_format($reseller['wallet_balance'] ?? 0, 2); ?>
                    </div>
                    <p style="color: var(--gray-600);">Available Balance</p>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Withdrawal History</h3>
            </div>
            <div class="card-body">
                <?php if (empty($withdrawals)): ?>
                    <p style="text-align: center; color: var(--gray-500); padding: 2rem;">No withdrawal history</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Requested</th>
                                    <th>Processed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($withdrawals as $withdrawal): ?>
                                    <tr>
                                        <td><strong>#<?php echo str_pad($withdrawal['request_id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                        <td>₱<?php echo number_format($withdrawal['amount'], 2); ?></td>
                                        <td>
                                            <span class="badge badge-<?php 
                                                echo match($withdrawal['status']) {
                                                    'pending' => 'warning',
                                                    'approved' => 'info',
                                                    'completed' => 'success',
                                                    'rejected' => 'danger',
                                                    default => 'secondary'
                                                };
                                            ?>">
                                                <?php echo ucfirst($withdrawal['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($withdrawal['created_at'])); ?></td>
                                        <td><?php echo $withdrawal['processed_at'] ? date('M d, Y', strtotime($withdrawal['processed_at'])) : '-'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-money-bill-wave"></i> Request Withdrawal</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>wallet/withdraw">
                    <div class="form-group">
                        <label>Amount (₱)</label>
                        <input type="number" name="amount" class="form-control" 
                               min="100" step="0.01" required 
                               max="<?php echo $reseller['wallet_balance'] ?? 0; ?>">
                        <small style="color: var(--gray-600); display: block; margin-top: 0.25rem;">
                            Minimum withdrawal: ₱100.00
                        </small>
                    </div>

                    <div style="background: var(--gray-100); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-700);">
                            <strong>GCash Account:</strong><br>
                            <?php echo htmlspecialchars($reseller['gcash_number'] ?? 'Not set'); ?><br>
                            <?php if ($reseller['gcash_name']): ?>
                                <small><?php echo htmlspecialchars($reseller['gcash_name']); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Submit Request
                    </button>
                </form>

                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-300);">
                    <h4 style="font-size: 0.875rem; color: var(--gray-700); margin-bottom: 0.75rem;">Processing Time:</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600);">
                        Withdrawal requests are processed within 1-3 business days.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.row {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 1.5rem;
}
@media (max-width: 768px) {
    .row {
        grid-template-columns: 1fr;
    }
}
</style>
