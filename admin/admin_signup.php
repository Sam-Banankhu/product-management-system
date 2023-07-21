<!DOCTYPE html>
<html>
<head>
    <title>Product Management System - Admin Signup</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_login.css">
    <style>
        .container {
            max-width: 400px; 
            margin-top: 50px;
        }
        .form-group label {
            font-weight: bold; 
        }
        .btn-primary {
            width: 100%; 
        }
        .btn-link {
            display: block; 
            margin-top: 10px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Admin Signup</h1>
        <?php if (isset($signupError)): ?>
            <div class="alert alert-danger"><?php echo $signupError; ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="admin_signup_process.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select class="form-control" id="role" name="role">
                    <option value="super admin">Super Admin</option>
                    <option value="regular admin">Regular Admin</option>
                </select>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Signup</button>
            </div>
            <div class="form-group text-center">
                <a href="admin_login.php" class="btn btn-link">Already have an account? Login</a>
            </div>
        </form>
    </div>
    <script src="../js/bootstrap.min.js"></script>
</body>
</html>
