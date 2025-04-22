@extends('admins.layout')

@section('title', 'Profile Settings')
@section('styles')
<style>
    /* Modern CSS Reset */
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    /* Container Styles */
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .settings-header {
        margin-bottom: 1.5rem;
    }

    .settings-header h1 {
        font-size: 1.75rem;
        color: #333;
        font-weight: 600;
        margin: 0;
    }

    /* Alert Styles */
    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background-color: #ecfdf5;
        border: 1px solid #10b981;
        color: #065f46;
    }

    .alert-error {
        background-color: #fef2f2;
        border: 1px solid #ef4444;
        color: #b91c1c;
    }

    .alert-icon {
        margin-right: 0.75rem;
        font-weight: bold;
    }

    .alert-message {
        flex: 1;
    }

    .alert-close {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        color: inherit;
    }

    /* Card Styles */
    .settings-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2rem;
    }

    /* Form Styles */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        flex: 1;
        min-width: 250px;
    }

    .password-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #555;
        margin-bottom: 0.5rem;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        color: #777;
        user-select: none;
    }

    input[type="text"],
    input[type="password"],
    input[type="number"] {
        width: 100%;
        padding: 0.75rem;
        padding-left: 2.5rem;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 1rem;
        color: #333;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="number"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        background-color: #fff;
    }

    input::placeholder {
        color: #aaa;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        cursor: pointer;
        user-select: none;
        color: #777;
    }

    /* Section Divider */
    .section-divider {
        margin: 2rem 0 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .section-divider h2 {
        font-size: 1.25rem;
        color: #444;
        font-weight: 600;
        margin: 0;
    }

    /* Button Styles */
    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-save {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        background-color: #2563eb;
        transform: translateY(-1px);
    }

    /* Error Message */
    .error-message {
        display: block;
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.375rem;
    }

    /* OTP Form Styles */
    .otp-section {
        margin-top: 1.5rem;
        display: none;
        /* Initially hidden */
    }

    .otp-section.active {
        display: block;
    }

    .otp-section label {
        margin-bottom: 0.5rem;
    }

    .otp-section input {
        width: 100%;
        max-width: 250px;
    }

    .resend-btn {
        display: inline-block;
        margin-top: 0.5rem;
        color: #3b82f6;
        cursor: pointer;
        border: 1px solid #3b82f6;
        border-radius: 4px;
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .resend-btn:hover {
        background-color: #3b82f6;
        color: white;
    }

    .resend-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        border-color: #94a3b8;
        color: #94a3b8;
    }

    /* Password strength indicator */
    .password-strength {
        margin-top: 0.5rem;
        height: 5px;
        border-radius: 5px;
        background-color: #ddd;
        overflow: hidden;
        position: relative;
    }

    .password-strength-meter {
        height: 100%;
        width: 0;
        transition: width 0.3s ease;
    }

    .weak {
        background-color: #ef4444;
        width: 33%;
    }

    .medium {
        background-color: #f59e0b;
        width: 66%;
    }

    .strong {
        background-color: #10b981;
        width: 100%;
    }

    .password-tips {
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #666;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .settings-container {
            padding: 1rem;
        }

        .settings-card {
            padding: 1.5rem;
        }

        .form-group {
            min-width: 100%;
        }
    }
</style>
@endsection

@section('content')
@include('admins.adminheader')

<div class="dashboard-wrapper">
    <div class="settings-container">
        <div class="settings-header">
            <h1>Profile Settings</h1>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            <span class="alert-message">{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.style.display='none';">×</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-icon">!</span>
            <span class="alert-message">{{ session('error') }}</span>
            <button class="alert-close" onclick="this.parentElement.style.display='none';">×</button>
        </div>
        @endif

        <div class="settings-card">
            <form id="profileForm" action="{{ route('admin.updateProfile') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" placeholder="Enter your name" required>
                        </div>
                        @error('name')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span id="name-error" class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-wrapper">
                            <span class="input-icon">📱</span>
                            <input type="text" id="phone" name="phone" value="{{ auth()->user()->mobile }}" placeholder="Enter your phone number" required>
                        </div>
                        @error('phone')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span id="phone-error" class="error-message"></span>
                    </div>
                </div>

                <div class="otp-section" id="otpSection">
                    <label for="otp">Enter OTP</label>
                    <input type="number" id="otp" name="otp" placeholder="Enter OTP" required>
                    <button type="button" class="resend-btn" id="resendOtpBtn">Resend OTP</button>
                    @error('otp')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                    <span id="otp-error" class="error-message"></span>
                </div>

                <div class="section-divider">
                    <h2>Change Password</h2>
                </div>

                <div class="form-row password-row">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="password" name="password" placeholder="Enter new password">
                            <span class="toggle-password" onclick="togglePasswordVisibility('password')">👁️</span>
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-meter" id="passwordStrength"></div>
                        </div>
                        <div class="password-tips">
                            Password must be at least 8 characters with numbers and special characters
                        </div>
                        @error('password')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        <span id="password-error" class="error-message"></span>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                            <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation')">👁️</span>
                        </div>
                        <span id="confirm-password-error" class="error-message"></span>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" id="requestOtpBtn" class="btn-save">Request OTP</button>
                    <button type="submit" id="saveChangesBtn" class="btn-save" style="display: none;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Password visibility toggle
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const toggleBtn = input.nextElementSibling;

        if (input.type === 'password') {
            input.type = 'text';
            toggleBtn.textContent = '👁️‍🗨️';
        } else {
            input.type = 'password';
            toggleBtn.textContent = '👁️';
        }
    }

    // DOM elements
    const form = document.getElementById('profileForm');
    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const otpInput = document.getElementById('otp');
    const otpSection = document.getElementById('otpSection');
    const requestOtpBtn = document.getElementById('requestOtpBtn');
    const saveChangesBtn = document.getElementById('saveChangesBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const passwordStrength = document.getElementById('passwordStrength');

    // Error elements
    const nameError = document.getElementById('name-error');
    const phoneError = document.getElementById('phone-error');
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('confirm-password-error');
    const otpError = document.getElementById('otp-error');

    // Validation functions
    function validateName() {
        if (!nameInput.value.trim()) {
            nameError.textContent = 'Name is required';
            return false;
        }
        nameError.textContent = '';
        return true;
    }

    function validatePhone() {
        const phoneRegex = /^\+?[0-9]{10,15}$/;
        if (!phoneInput.value.trim()) {
            phoneError.textContent = 'Phone number is required';
            return false;
        }
        if (!phoneRegex.test(phoneInput.value.trim())) {
            phoneError.textContent = 'Please enter a valid phone number';
            return false;
        }
        phoneError.textContent = '';
        return true;
    }

    function validatePassword() {
        if (passwordInput.value) {
            if (passwordInput.value.length < 8) {
                passwordError.textContent = 'Password must be at least 8 characters';
                return false;
            }
            if (!/[0-9]/.test(passwordInput.value)) {
                passwordError.textContent = 'Password must contain at least one number';
                return false;
            }
            if (!/[^A-Za-z0-9]/.test(passwordInput.value)) {
                passwordError.textContent = 'Password must contain at least one special character';
                return false;
            }
        }
        passwordError.textContent = '';
        return true;
    }

    function validateConfirmPassword() {
        if (passwordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordError.textContent = 'Passwords do not match';
            return false;
        }
        confirmPasswordError.textContent = '';
        return true;
    }

    function validateOtp() {
        if (!otpInput.value.trim()) {
            otpError.textContent = 'OTP is required';
            return false;
        }
        if (!/^\d{6}$/.test(otpInput.value.trim())) {
            otpError.textContent = 'OTP must be 6 digits';
            return false;
        }
        otpError.textContent = '';
        return true;
    }

    // Check password strength
    function checkPasswordStrength(password) {
        let strength = 0;

        if (password.length >= 8) strength += 1;
        if (/[0-9]/.test(password)) strength += 1;
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;

        if (strength === 0) {
            passwordStrength.className = 'password-strength-meter';
            passwordStrength.style.width = '0';
        } else if (strength === 1) {
            passwordStrength.className = 'password-strength-meter weak';
        } else if (strength === 2) {
            passwordStrength.className = 'password-strength-meter medium';
        } else {
            passwordStrength.className = 'password-strength-meter strong';
        }
    }

    // Event listeners
    nameInput.addEventListener('blur', validateName);
    phoneInput.addEventListener('blur', validatePhone);
    passwordInput.addEventListener('blur', validatePassword);
    confirmPasswordInput.addEventListener('blur', validateConfirmPassword);
    otpInput.addEventListener('blur', validateOtp);

    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
    });

    // Request OTP
    requestOtpBtn.addEventListener('click', function() {
        if (!validateName() || !validatePhone() || !validatePassword() || !validateConfirmPassword()) {
            return;
        }

        // Send OTP request via fetch
        fetch('/admin/settings/send-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    mobile: phoneInput.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    toastr.error(data.error);
                } else if (data.message) {
                    toastr.success(data.message);
                    // Show OTP section and save button
                    otpSection.classList.add('active');
                    requestOtpBtn.style.display = 'none';
                    saveChangesBtn.style.display = 'block';
                    // Start OTP timer
                    startOtpTimer();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending OTP. Please try again.');
            });
    });

    // Resend OTP
    let otpTimer = null;

    resendOtpBtn.addEventListener('click', function() {
        if (resendOtpBtn.disabled) return;

        fetch('/admin/settings/resend-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    mobile: phoneInput.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    if (data.seconds_remaining) {
                        startOtpTimer(data.seconds_remaining);
                    }
                } else {
                    alert(data.message);
                    startOtpTimer();
                    // For development, auto-fill OTP if provided
                    // if (data.otp) {
                    //     otpInput.value = data.otp;
                    // }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while resending OTP. Please try again.');
            });
    });

    function startOtpTimer(timeLeft = 60) {
        clearInterval(otpTimer);

        resendOtpBtn.disabled = true;

        otpTimer = setInterval(function() {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(otpTimer);
                resendOtpBtn.disabled = false;
                resendOtpBtn.innerText = 'Resend OTP';
            } else {
                resendOtpBtn.innerText = `Resend OTP (${timeLeft}s)`;
            }
        }, 1000);
    }

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateName() || !validatePhone() || !validatePassword() || !validateConfirmPassword() || !validateOtp()) {
            return;
        }

        this.submit();
    });
</script>
@endsection