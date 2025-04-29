    // Toggle between login and register forms
    document.getElementById('show-register').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('register-form').classList.remove('hidden');
        document.getElementById('login-tab').classList.remove('bg-blue-50', 'text-blue-600');
        document.getElementById('login-tab').classList.add('text-gray-500');
        document.getElementById('register-tab').classList.add('bg-blue-50', 'text-blue-600');
        document.getElementById('register-tab').classList.remove('text-gray-500');
    });

    document.getElementById('show-login').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('register-form').classList.add('hidden');
        document.getElementById('login-form').classList.remove('hidden');
        document.getElementById('register-tab').classList.remove('bg-blue-50', 'text-blue-600');
        document.getElementById('register-tab').classList.add('text-gray-500');
        document.getElementById('login-tab').classList.add('bg-blue-50', 'text-blue-600');
        document.getElementById('login-tab').classList.remove('text-gray-500');
    });

    // Tab click handlers
    document.getElementById('login-tab').addEventListener('click', function() {
        document.getElementById('register-form').classList.add('hidden');
        document.getElementById('login-form').classList.remove('hidden');
        this.classList.add('bg-blue-50', 'text-blue-600');
        this.classList.remove('text-gray-500');
        document.getElementById('register-tab').classList.remove('bg-blue-50', 'text-blue-600');
        document.getElementById('register-tab').classList.add('text-gray-500');
    });

    document.getElementById('register-tab').addEventListener('click', function() {
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('register-form').classList.remove('hidden');
        this.classList.add('bg-blue-50', 'text-blue-600');
        this.classList.remove('text-gray-500');
        document.getElementById('login-tab').classList.remove('bg-blue-50', 'text-blue-600');
        document.getElementById('login-tab').classList.add('text-gray-500');
    });
