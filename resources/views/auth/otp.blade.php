@include('layouts.partials.head')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link rel="stylesheet" href="{{ asset('css/otp.css') }}">


<body class="font-sans text-gray-900 antialiased auth-bg">
    <div class="w-full max-w-md space-y-10 p-10 rounded-2xl auth-card">
        <div class="text-center">
            <div class="inline-block p-4 mb-4 rounded-full auth-logo bg-gradient-to-r from-indigo-500 to-purple-600">
                <i class="fa-solid fa-shield fa-2x text-white" style="display: inline-block !important;"></i>
            </div>
            <h2 class="text-2xl font-bold mb-2 text-center text-white-700 auth-title">Security Verification</h2>
            <p class="text-gray-400 text-center mb-6 auth-subtitle">
                We've sent a 6-digit verification code to your email address
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fa-solid fa-info-circle text-blue-500 mr-2" style="display: inline-block !important;"></i>
                    <p class="text-sm text-blue-700">
                        Check your email inbox and spam folder for the verification code
                    </p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('auth.otp.verify') }}" id="otp-form">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-3 text-center">
                    Enter Verification Code
                </label>
                <div class="flex justify-center gap-3 mb-4">
                    @for ($i = 1; $i <= 6; $i++)
                        <input 
                            type="text" 
                            inputmode="numeric" 
                            maxlength="1" 
                            name="otp[]" 
                            id="otp{{ $i }}" 
                            class="font-bold otp-input text-center text-xl border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 bg-white text-gray-800" 
                            autocomplete="one-time-code" 
                            required 
                            placeholder="0"
                        />
                    @endfor
                </div>
                @error('code')
                    <div class="text-red-400 text-xs mb-2 text-center bg-red-900/20 rounded p-2">
                        <i class="fa-solid fa-exclamation-triangle mr-1" style="display: inline-block !important;"></i>
                        {{ $message }}
                    </div>
                @enderror
                <input type="hidden" name="code" id="otp-code" />
            </div>
            
            <button type="submit" class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white border border-transparent rounded-lg shadow-sm auth-button bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                <i class="fa-solid fa-check mr-2" style="display: inline-block !important;"></i>
                Verify & Continue
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <div class="text-sm text-gray-400 mb-3">
                Didn't receive the code?
            </div>
            <button type="button" id="resend-otp-btn" class="text-indigo-400 cursor-pointer hover:text-indigo-300 bg-transparent border-0 p-0 font-medium transition-colors duration-200">
                <i class="fa-solid fa-paper-plane mr-1" style="display: inline-block !important;"></i>
                Resend Code
            </button>
            <div id="resend-status" class="mt-2 text-xs"></div>
        </div>
        
        <div class="mt-6 p-4 bg-gray-800/50 rounded-lg">
            <div class="flex items-start">
                <i class="fa-solid fa-lightbulb text-yellow-400 mt-1 mr-3" style="display: inline-block !important;"></i>
                <div class="text-xs text-gray-300">
                    <strong class="text-yellow-400">Pro tip:</strong> 
                    The code expires in 10 minutes. Make sure to enter it quickly and accurately.
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
        // Check if Font Awesome is loaded and add fallback if not
        function checkFontAwesome() {
            const testIcon = document.createElement('i');
            testIcon.className = 'fa-solid fa-check';
            testIcon.style.position = 'absolute';
            testIcon.style.left = '-9999px';
            document.body.appendChild(testIcon);
            
            const computedStyle = window.getComputedStyle(testIcon, ':before');
            const content = computedStyle.getPropertyValue('content');
            
            document.body.removeChild(testIcon);
            
            if (!content || content === 'none' || content === 'normal') {
                // Font Awesome not loaded, use fallback
                replaceWithFallbackIcons();
            }
        }
        
        function replaceWithFallbackIcons() {
            const icons = document.querySelectorAll('.fa-solid');
            icons.forEach(icon => {
                const className = icon.className;
                if (className.includes('fa-shield-check')) {
                    icon.innerHTML = '🔒';
                    icon.className = 'icon-fallback text-2xl';
                } else if (className.includes('fa-info-circle')) {
                    icon.innerHTML = 'ℹ️';
                    icon.className = 'icon-fallback';
                } else if (className.includes('fa-exclamation-triangle')) {
                    icon.innerHTML = '⚠️';
                    icon.className = 'icon-fallback';
                } else if (className.includes('fa-check')) {
                    icon.innerHTML = '✅';
                    icon.className = 'icon-fallback';
                } else if (className.includes('fa-paper-plane')) {
                    icon.innerHTML = '📤';
                    icon.className = 'icon-fallback';
                } else if (className.includes('fa-lightbulb')) {
                    icon.innerHTML = '💡';
                    icon.className = 'icon-fallback';
                } else if (className.includes('fa-spinner')) {
                    icon.innerHTML = '🔄';
                    icon.className = 'icon-fallback';
                }
            });
        }
        
        // Run check after page loads
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(checkFontAwesome, 100);
        });
        
        // Auto-focus and move to next input
        const inputs = document.querySelectorAll('input[name="otp[]"]');
        
        inputs.forEach((input, idx) => {
            // Focus first input on page load
            if (idx === 0) {
                input.focus();
            }
            
            input.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
                
                if (this.value.length === 1 && idx < inputs.length - 1) {
                    inputs[idx + 1].focus();
                }
                if (this.value.length === 0 && idx > 0 && e.inputType === 'deleteContentBackward') {
                    inputs[idx - 1].focus();
                }
                
                // Update visual feedback
                if (this.value.length === 1) {
                    this.classList.add('border-green-500');
                } else {
                    this.classList.remove('border-green-500');
                }
            });
            
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && idx > 0) {
                    inputs[idx - 1].focus();
                }
            });
            
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = (e.clipboardData || window.clipboardData).getData('text');
                const numbers = pastedData.replace(/[^0-9]/g, '').slice(0, 6);
                
                inputs.forEach((input, index) => {
                    if (index < numbers.length) {
                        input.value = numbers[index];
                        input.classList.add('border-green-500');
                    }
                });
                
                if (numbers.length === 6) {
                    inputs[5].focus();
                }
            });
        });
        
        // On submit, join digits into hidden code field
        document.getElementById('otp-form').addEventListener('submit', function(e) {
            let code = '';
            inputs.forEach(input => code += input.value);
            document.getElementById('otp-code').value = code;
        });
        
        // Resend OTP functionality
        document.getElementById('resend-otp-btn').addEventListener('click', function() {
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1" style="display: inline-block !important;"></i> Sending...';
            
            fetch("{{ route('auth.otp.resend') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('resend-status').innerHTML = 
                    '<i class="fa-solid fa-check text-green-400 mr-1" style="display: inline-block !important;"></i>' + 
                    (data.message || 'Code resent successfully!');
                document.getElementById('resend-status').className = 'mt-2 text-xs text-green-400';
                
                // Clear OTP inputs
                inputs.forEach(input => {
                    input.value = '';
                    input.classList.remove('border-green-500');
                });
                inputs[0].focus();
                
                setTimeout(() => {
                    document.getElementById('resend-status').innerHTML = '';
                    this.disabled = false;
                    this.innerHTML = originalText;
                }, 5000);
            })
            .catch(() => {
                document.getElementById('resend-status').innerHTML = 
                    '<i class="fa-solid fa-exclamation-triangle text-red-400 mr-1" style="display: inline-block !important;"></i>' + 
                    'Error sending code. Please try again.';
                document.getElementById('resend-status').className = 'mt-2 text-xs text-red-400';
                this.disabled = false;
                this.innerHTML = originalText;
            });
        });
    </script>
</body> 