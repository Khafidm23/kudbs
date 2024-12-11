<?php
session_start();

include '../config/config.php';

$err = "";
$username = "";
$rememberme = "";

if (isset($_COOKIE['cookie_username'])) {
    $cookie_username = $_COOKIE['cookie_username'];
    $stmt = $conn->prepare("SELECT username, password FROM tb_user WHERE username = ?");
    $stmt->bind_param("s", $cookie_username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($cookie_username, $user['password'])) {
        $_SESSION['session_username'] = $user['username'];
    }
}

if (isset($_SESSION['session_username'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $rememberme = isset($_POST['rememberme']) ? 1 : 0;

    if (empty($username) || empty($password)) {
        $err .= "<li>Silahkan masukkan username & password</li>";
    } else {
        $stmt = $conn->prepare("SELECT username, password FROM tb_user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            // Username tidak ditemukan
            $err .= "Username tidak ditemukan";
        } elseif (!password_verify($password, $user['password'])) {
            // Password salah
            $err .= "Password Anda salah";
        } else {
            // Login berhasil
            $_SESSION['session_username'] = $user['username'];

            if ($rememberme) {
                setcookie("cookie_username", $user['username'], time() + (86400), "/", null, true, true);
                setcookie("cookie_password", password_hash($password, PASSWORD_DEFAULT), time() + (86400), "/", null, true, true);
            }

            header("Location: ../index.php");
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Login page for Rekap Jasa Excavator">
    <title>Login - Rekap Jasa Excavator</title>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, blue, black);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            animation: fadeIn 1s ease-in-out;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slideIn 0.5s ease-in-out;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
            animation: fadeIn 1s ease-in-out;
        }

        .login-container input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background: linear-gradient(135deg, blue, black);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container input[type="submit"]:hover {
            background: linear-gradient(135deg, black, red);
        }

        .login-container .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 10px;
        }

        .login-container .remember-me input {
            margin-right: 5px;
        }

        .panel-body {
            font-size: 14px;
            color: red;
            margin-top: 20px;
        }

        .kembali button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background: linear-gradient(135deg, blue, black);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            
        }

        .kembali button:hover {
            background: linear-gradient(135deg, black, red);
        }

    </style>
</head>

<body>
<div class="kembali" style="position: absolute; top: 10px; right: 10px;">
<a href="../for_user/index.php"><button>FOR USER</button></a>
</div>
    <div class="login-container">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>"
                required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <div class="remember-me">
                <input type="checkbox" name="rememberme" id="rememberme" value="1" <?php if ($rememberme == '1')
                    echo 'checked'; ?>>
                <label for="rememberme">Remember me</label>
            </div>
            <input type="submit" name="login" value="Login">
        </form>
        
        <div class="panel-body">
            <?php if ($err) { ?>
                <div id="login-alert" class="alert-danger col-sm-12">
                    <i><?php echo $err ?></i>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>