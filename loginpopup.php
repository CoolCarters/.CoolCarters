<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #1a1a1a;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #d9d9d9;
            width: 400px;
            margin: 100px auto;
            border-radius: 40px;
            text-align: center;
            padding: 40px 20px;
        }

        .container h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .container p {
            font-size: 14px;
            margin: 10px 0;
        }

        .edit-email {
            color: red;
            margin: 10px 0;
            font-weight: bold;
            cursor: pointer;
        }

        .code-label {
            display: inline-block;
            margin-right: 10px;
            font-size: 20px;
            color: white;
            transform: translateY(-20px);
        }

        .code-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        .code-inputs input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 24px;
            border: none;
            border-radius: 8px;
        }

        .done-button {
            margin-top: 10px;
        }

        .done-button button {
            padding: 10px 20px;
            background-color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>VERIFICATION</h2>
        <p>We have send a code to your email. Please verify<br />accordingly.</p>
        <div class="edit-email">Edit Email Id</div>

        <div style="display: flex; justify-content: center; align-items: center;">
            <div class="code-label">Code:</div>
            <div class="code-inputs">
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
                <input type="text" maxlength="1" />
            </div>
        </div>

        <div class="done-button">
            <button>Done</button>
        </div>
    </div>
</body>

</html>