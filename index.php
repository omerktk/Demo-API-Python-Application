<?php
   // Check for authentication token
   $token = $_GET['token'] ?? '';
   
   require 'api/config/config.php';
   
   // Function to check token validity
   function checkToken($token) {
       global $pdo;
       $stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
       $stmt->execute([$token]);
       return $stmt->fetch() ? true : false;
   }
   
   // Validate token
   $authValid = $token && checkToken($token);
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>API Documentation</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <style>
         body {
         background-color: #f8f9fa;
         }
         .container {
         margin-top: 20px;
         padding: 20px;
         background-color: white;
         border-radius: 10px;
         box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
         }
         h1 {
         font-size: 2.5rem;
         }
         pre {
         background-color: #f1f1f1;
         padding: 10px;
         border-radius: 5px;
         font-size: 0.9rem;
         }
         .btn {
         margin-top: 10px;
         }
         .modal-content {
         border-radius: 10px;
         }
         .modal-header {
         border-bottom: none;
         }
         .modal-body {
         padding-top: 0;
         }
         .close {
         outline: none;
         }
      </style>
   </head>
   <body>
      <div class="container">
         <h1 class="my-4">API Documentation</h1>
         <?php if (!$authValid): ?>
         <p>Welcome to the API documentation. Below are the available endpoints. Use the <strong>Test</strong> buttons to try them out.</br>
            The API URL is: <code><?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/demo/api'); ?></code>
         </p>
         <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading">Authentication Required</h4>
            <p>Please provide a valid token in the URL query parameter <code>?token=YOUR_TOKEN</code></p>
            <form method="GET" class="mb-4">
               <div class="form-group">
                  <label for="tokenInput">API Token</label>
                  <input type="text" class="form-control" id="tokenInput" name="token" placeholder="Enter your token here">
               </div>
               <button type="submit" class="btn btn-primary">Submit</button>
            </form>
         </div>
         <?php else: ?>
         <p>Welcome to the API documentation. Below are the available endpoints. Use the <strong>Test</strong> buttons to try them out.</br>
            The API URL is: <code><?php echo htmlspecialchars((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/demo/api'); ?></code>
         </p>
         <div id="accordion">
            <div class="card">
               <a class="card-link" data-toggle="collapse" href="#Authentication">
                  <div class="card-header">
                     Authentication
                  </div>
               </a>
               <div id="Authentication" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                     <!-- Authentication Token Section -->
                     <h3>Authentication Token</h3>
                     <p>The authentication token is generated using SHA-256 hashing. The token is created by concatenating the username and password with an '@' symbol and then hashing the result.</p>
                     <p>For example, using the username <code>admin</code> and password <code>admin</code>:</p>
                     <pre>Token: <code>3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f</code></pre>
                     <p>To generate your own token, follow these steps:</p>
                     <ol>
                        <li>Concatenate the username and password with an '@' symbol. For example: <code>username:password</code> becomes <code>admin@admin</code></li>
                        <li>Hash the result using SHA-256. In this case, <code>admin@admin</code> produces <code>3b408cb48548b5037822c10eb0976b3cbf2cee3bf9c708796bf03941fbecd80f</code></li>
                     </ol>
                     <!-- Authentication Endpoint -->
                     <h3>Authentication Endpoint (`api/auth/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "failure", "message": "Invalid token"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/auth/', { token: '<?= $token ?>' })">Test Authentication</button>
                  </div>
               </div>
            </div>
            </br>
            <div class="card">
               <a class="card-link" data-toggle="collapse" href="#Users">
                  <div class="card-header">
                     Users
                  </div>
               </a>
               <div id="Users" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                     <h3>Create User Endpoint (`api/users/create/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"fullname": "full_name", "newtoken": "new_token", "token": "your_auth_token"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "User created successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "Missing required fields"}</code></pre>
                     <pre><code>{"status": "error", "message": "Failed to create user"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/users/create/', { fullname: 'John Doe', newtoken: 'new_token_value', token: '<?= $token ?>' })">Test Create User</button>
                     <br>
                     <h3>Read Users Endpoint (`api/users/read/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "users": [...]}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/users/read/', { token: '<?= $token ?>' })">Test Read Users</button>
                     <h3>Update User Endpoint (`api/users/update/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"id": "user_id", "fullname": "new_full_name", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "User updated successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "User ID or new full name missing"}</code></pre>
                     <pre><code>{"status": "error", "message": "Failed to update user"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/users/update/', { id: 1, fullname: 'Jane Doe', token: '<?= $token ?>'  })">Test Update User</button>
                     <h3>Delete User Endpoint (`api/users/delete/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"id": "user_id", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "User deleted successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "ID is required"}</code></pre>
                     <pre><code>{"status": "error", "message": "Cannot delete your own account"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/users/delete/', { id: 1, token: '<?= $token ?>' })">Test Delete User</button>
                     <h3>Find User by Token Endpoint (`api/users/findtoken/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"findtoken": "token_to_find", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "user": { ... }}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "Findtoken is required"}</code></pre>
                     <pre><code>{"status": "error", "message": "User not found"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/users/findtoken/', { findtoken: 'token_to_find' , token: '<?= $token ?>'})">Test Find User by Token</button>
                  </div>
               </div>
            </div>
            </br>
            <div class="card">
               <a class="card-link" data-toggle="collapse" href="#Transactions">
                  <div class="card-header">
                     Transactions
                  </div>
               </a>
               <div id="Transactions" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                     <h3>Create Transaction Endpoint (`api/transactions/create/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"from": "user_id", "to": "user_id", "type": "transaction_type", "amount": amount, "datetime": "datetime", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "Transaction created successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "All fields are required"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/transactions/create/', { from: 1, to: 2, type: 'transfer', amount: 100, datetime: '2024-07-30 12:00:00', token: '<?= $token ?>' })">Test Create Transaction</button>
                     <h3>Read Transactions Endpoint (`api/transactions/read/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "transactions": [...]}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/transactions/read/', { token: '<?= $token ?>' })">Test Read Transactions</button>
                     <h3>Update Transaction Endpoint (`api/transactions/update/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"id": "transaction_id", "from": "user_id", "to": "user_id", "type": "transaction_type", "amount": amount, "datetime": "datetime", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "Transaction updated successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "All fields are required"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/transactions/update/', { id: 1, from: 1, to: 2, type: 'update', amount: 150, datetime: '2024-07-31 12:00:00', token: '<?= $token ?>' })">Test Update Transaction</button>
                     <h3>Delete Transaction Endpoint (`api/transactions/delete/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"id": "transaction_id", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "Transaction deleted successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "Transaction ID missing"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/transactions/delete/', { id: 1, token: '<?= $token ?>' })">Test Delete Transaction</button>
                  </div>
               </div>
            </div>
            </br>
            <div class="card">
               <a class="card-link" data-toggle="collapse" href="#Logs">
                  <div class="card-header">
                     Logs
                  </div>
               </a>
               <div id="Logs" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                     <!-- Authentication Endpoint -->
                     <h3>Create Log Endpoint (`api/logs/create/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"log": "log_message", "datetime": "datetime", "token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "message": "Log created successfully"}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <pre><code>{"status": "error", "message": "Log and datetime are required"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/logs/create/', { log: 'Sample log message', datetime: '2024-07-30 12:00:00', token: '<?= $token ?>' })">Test Create Log</button>
                     <h3>Read Logs Endpoint (`api/logs/read/`)</h3>
                     <p>Method: POST</p>
                     <p>Request JSON: <code>{"token": "your_token_here"}</code></p>
                     <p><strong>Success Responses:</strong></p>
                     <pre><code>{"status": "success", "logs": [...]}</code></pre>
                     <p><strong>Error Responses:</strong></p>
                     <pre><code>{"status": "error", "message": "Invalid token"}</code></pre>
                     <button class="btn btn-primary" onclick="openModal('api/logs/read/', { token: '<?= $token ?>' })">Test Read Logs</button>
                  </div>
               </div>
            </div>
            </br>
         </div>
         <?php endif; ?>
      </div>
      <script>
         function openModal(url, data) {
             // Populate the modal with the URL and data
             document.getElementById('apiUrl').value = url;
             document.getElementById('apiData').value = JSON.stringify(data, null, 2);
         
             // Show the modal
             $('#apiModal').modal('show');
         }
         
         function testEndpoint() {
             const url = document.getElementById('apiUrl').value;
             const data = JSON.parse(document.getElementById('apiData').value);
         
             fetch(url, {
                 method: 'POST',
                 headers: { 'Content-Type': 'application/json' },
                 body: JSON.stringify(data)
             })
             .then(response => response.json())
             .then(data => document.getElementById('apiResponse').innerText = JSON.stringify(data, null, 2))
             .catch(error => document.getElementById('apiResponse').innerText = 'Error: ' + error.message);
         }
      </script>
      <!-- Modal for API Testing -->
      <div class="modal fade" id="apiModal" tabindex="-1" role="dialog" aria-labelledby="apiModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="apiModalLabel">Test API Endpoint</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="form-group">
                     <label for="apiUrl">API Endpoint</label>
                     <input type="text" class="form-control" id="apiUrl" readonly>
                  </div>
                  <div class="form-group">
                     <label for="apiData">Request Data (JSON)</label>
                     <textarea class="form-control" id="apiData" rows="10"></textarea>
                  </div>
                  <button type="button" class="btn btn-primary" onclick="testEndpoint()">Test</button>
                  <pre id="apiResponse"></pre>
               </div>
            </div>
         </div>
      </div>
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   </body>
</html>