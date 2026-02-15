<!-- Add User -->
<div class="fade-in">
    <!-- Page Header -->
    <div class="row">
        <div class="col col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-plus"></i> Add New User
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>user" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Form -->
    <div class="row">
        <div class="col col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> User Information
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>user/add" enctype="multipart/form-data" id="userForm">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="full_name">Full Name <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required 
                                   placeholder="Enter full name" value="<?php echo isset($user['full_name']) ? htmlspecialchars($user['full_name']) : ''; ?>">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email Address <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                   placeholder="user@example.com" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>">
                            <small class="text-muted">Will be used for login</small>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   placeholder="+63 XXX XXX XXXX" value="<?php echo isset($user['phone']) ? htmlspecialchars($user['phone']) : ''; ?>">
                        </div>

                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" 
                                      placeholder="Enter complete address"><?php echo isset($user['address']) ? htmlspecialchars($user['address']) : ''; ?></textarea>
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <label for="role">User Role <span style="color: red;">*</span></label>
                            <select class="form-control" id="role" name="role" required onchange="showRoleInfo(this.value)">
                                <option value="">-- Select Role --</option>
                                <option value="admin">Admin - Full system access</option>
                                <option value="reseller">Reseller - Can sell products and earn commission</option>
                                <option value="customer">Customer - Can purchase products</option>
                            </select>
                        </div>

                        <!-- Role Info Box -->
                        <div id="roleInfoBox" style="display: none; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <i class="fas fa-info-circle"></i> <span id="roleInfoText"></span>
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

                        <!-- Profile Photo -->
                        <div class="form-group">
                            <label for="photo">Profile Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="previewPhoto(this)" style="display: none;">
                            
                            <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                                <!-- Preview Image -->
                                <div id="photoPreview" style="width: 120px; height: 120px; border: 3px dashed #ddd; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <img id="preview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <i class="fas fa-user" id="defaultIcon" style="font-size: 50px; color: #ccc;"></i>
                                </div>
                                
                                <!-- Upload Actions -->
                                <div style="flex: 1;">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('photo').click();" style="margin-bottom: 10px;">
                                        <i class="fas fa-upload"></i> Import Image
                                    </button>
                                    <button type="button" class="btn btn-outline" onclick="clearPhoto()" id="clearPhotoBtn" style="margin-bottom: 10px; margin-left: 10px; display: none;">
                                        <i class="fas fa-times"></i> Remove
                                    </button>
                                    <div id="fileName" style="font-size: 14px; color: #666; margin-top: 5px;"></div>
                                    <small class="text-muted" style="display: block; margin-top: 5px;">
                                        Recommended size: 200x200px. Max 2MB.<br>
                                        Supported formats: JPG, PNG, GIF
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status">Account Status <span style="color: red;">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">Active - Can login and access system</option>
                                <option value="inactive">Inactive - Account suspended</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group" style="margin-top: 30px;">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Add User
                            </button>
                            <a href="<?php echo BASE_URL; ?>user" class="btn btn-outline btn-lg" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col col-4">
            <!-- User Roles Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt"></i> User Roles
                    </h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: var(--primary-pink);">
                            <i class="fas fa-user-shield"></i> Admin
                        </strong>
                        <p style="margin: 5px 0 0 0; font-size: 14px;">Full access to all features including users, products, orders, and settings.</p>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <strong style="color: var(--primary-pink);">
                            <i class="fas fa-user-tie"></i> Reseller
                        </strong>
                        <p style="margin: 5px 0 0 0; font-size: 14px;">Can sell products and earn commissions. Requires approval before activation.</p>
                    </div>
                    <div>
                        <strong style="color: var(--primary-pink);">
                            <i class="fas fa-user"></i> Customer
                        </strong>
                        <p style="margin: 5px 0 0 0; font-size: 14px;">Can browse and purchase products. Standard user account.</p>
                    </div>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock"></i> Security Tips
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="padding-left: 20px; margin: 0;">
                        <li style="margin-bottom: 8px;">Use strong passwords (mix of letters, numbers, symbols)</li>
                        <li style="margin-bottom: 8px;">Never share admin credentials</li>
                        <li style="margin-bottom: 8px;">Review reseller applications carefully</li>
                        <li>Regularly check user activity logs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>// Preview photo
function previewPhoto(input) {
    const preview = document.getElementById('preview');
    const defaultIcon = document.getElementById('defaultIcon');
    const fileName = document.getElementById('fileName');
    const clearBtn = document.getElementById('clearPhotoBtn');
    const previewContainer = document.getElementById('photoPreview');
    
    if (input.files && input.files[0]) {
        // Check file size (2MB max)
        if (input.files[0].size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            input.value = '';
            clearPhoto();
            return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Please select a valid image file (JPG, PNG, or GIF)');
            input.value = '';
            clearPhoto();
            return;
        }
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            defaultIcon.style.display = 'none';
            previewContainer.style.border = '3px solid var(--primary-pink)';
            fileName.innerHTML = '<i class="fas fa-check-circle" style="color: green;"></i> ' + input.files[0].name;
            clearBtn.style.display = 'inline-block';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Clear photo
function clearPhoto() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('preview');
    const defaultIcon = document.getElementById('defaultIcon');
    const fileName = document.getElementById('fileName');
    const clearBtn = document.getElementById('clearPhotoBtn');
    const previewContainer = document.getElementById('photoPreview');
    
    input.value = '';
    preview.src = '';
    preview.style.display = 'none';
    defaultIcon.style.display = 'block';
    previewContainer.style.border = '3px dashed #ddd';
    fileName.innerHTML = '';
    clearBtn.style.display = 'none';
}

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

// Show role information
function showRoleInfo(role) {
    const infoBox = document.getElementById('roleInfoBox');
    const infoText = document.getElementById('roleInfoText');
    
    if (!role) {
        infoBox.style.display = 'none';
        return;
    }
    
    const roleInfo = {
        'admin': {
            text: '<strong>Admin users</strong> have full control over the system. Be careful when assigning this role.',
            color: '#dc3545'
        },
        'reseller': {
            text: '<strong>Reseller accounts</strong> will need to be approved before they can start selling products.',
            color: '#ffc107'
        },
        'customer': {
            text: '<strong>Customer accounts</strong> are standard users who can browse and purchase products.',
            color: '#28a745'
        }
    };
    
    if (roleInfo[role]) {
        infoText.innerHTML = roleInfo[role].text;
        infoBox.style.display = 'block';
        infoBox.style.backgroundColor = roleInfo[role].color + '20';
        infoBox.style.borderLeft = '4px solid ' + roleInfo[role].color;
    }
}

// Form validation
document.getElementById('userForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match. Please try again.');
        document.getElementById('confirm_password').focus();
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long.');
        document.getElementById('password').focus();
        return false;
    }
    
    const role = document.getElementById('role').value;
    if (role === 'admin') {
        if (!confirm('You are creating an ADMIN account with full system access. Are you sure?')) {
            e.preventDefault();
            return false;
        }
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
</script>
