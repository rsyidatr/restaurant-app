<!-- Test Login Page for Debugging -->
<!DOCTYPE html>
<html>
<head>
    <title>Test Login Debug</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input, select { padding: 8px; width: 200px; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; }
        .result { margin: 20px 0; padding: 10px; background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Test Login Debug</h1>
    
    <div class="form-group">
        <label>Select User:</label>
        <select id="userSelect">
            <option value="admin@test.com">Admin (admin@test.com)</option>
            <option value="waiter@test.com">Waiter (waiter@test.com)</option>
            <option value="chef@test.com">Chef/Koki (chef@test.com)</option>
            <option value="customer@test.com">Customer (customer@test.com)</option>
            <option value="kitchen@test.com">Kitchen (kitchen@test.com)</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Select Guard:</label>
        <select id="guardSelect">
            <option value="web">Web</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
            <option value="customer">Customer</option>
        </select>
    </div>
    
    <button onclick="testLogin()">Test Login</button>
    <button onclick="testDirect()">Test Direct Access</button>
    
    <div id="result" class="result" style="display:none;"></div>
    
    <script>
        function testLogin() {
            const email = document.getElementById('userSelect').value;
            const guard = document.getElementById('guardSelect').value;
            
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/multi-session/login/${guard}`;
            
            // CSRF token
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);
            
            // Email
            const emailInput = document.createElement('input');
            emailInput.type = 'hidden';
            emailInput.name = 'email';
            emailInput.value = email;
            form.appendChild(emailInput);
            
            // Password  
            const passwordInput = document.createElement('input');
            passwordInput.type = 'hidden';
            passwordInput.name = 'password';
            passwordInput.value = 'password';
            form.appendChild(passwordInput);
            
            document.body.appendChild(form);
            form.submit();
        }
        
        function testDirect() {
            const email = document.getElementById('userSelect').value;
            window.location.href = `/debug-login/${email}`;
        }
    </script>
</body>
</html>
