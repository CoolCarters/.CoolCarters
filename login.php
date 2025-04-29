<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="./css/input.css">
</head>

<body>
    <H1>Log In</H1>
    <form action="login.php" method="post">
        <input type="email" name="email" placeholder="@gmail.com">
        <input type="password" name="password" placeholder="Password">
        <select name="roles" required>
            <option value="" hidden disabled selected>Role</option>
            <option value="Trader">Trader</option>
            <option value="Customer">Customer</option>
        </select>
        <button type="submit">Login</button>
        <label for="terms_and_conditions">I accept the terms and conditions: </label>
        <input type="checkbox" id="terms_and_conitions name=" terms_and_conditions required>
        <button type="submit">Register</button>
    </form>
</body>

</html>