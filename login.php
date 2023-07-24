<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>ログイン</title>
    <link rel="stylesheet" type="text/css" href="login.css">
    <meta charset="utf-8">
</head>

<body>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>
        <div class="login-container">
            <h2>ログイン</h2>
            <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                <label for="email">メールアドレス:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">パスワード:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">ログイン</button>
                <?php if(isset($_SESSION["err_login_message"])) {
                    echo "<p style='color: red;'>".$_SESSION["err_login_message"]."</p>";
                } ?>
            </form>
            <p>アカウントをお持ちでない方は<a href="register.php">新規登録ページ</a>へ</p>
        </div>
    <?php } ?>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
        <?php

        include "sql_connection.php";

        // フォームからの入力を取得
        $email = $_POST["email"];
        $password = $_POST["password"];

        
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                // ログイン成功時にindex.phpにリダイレクト
                $_SESSION["id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                header("Location: index.php");
                $_SESSION["err_login_message"] = null;
                exit;
            } else {
                $_SESSION["err_login_message"] = "メールアドレスまたはパスワードが間違っています。";
                header("Location: login.php");
                exit;
            }
        } else {
            // ログイン失敗
            $_SESSION["err_login_message"] = "メールアドレスまたはパスワードが間違っています。";
            header("Location: login.php");
            exit;
        }

        $conn->close();
        ?>
    <?php } ?>
</body>

</html>
