<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>ユーザー情報更新</title>
    <link rel="stylesheet" type="text/css" href="login.css">
    <meta charset="utf-8">
</head>

<body>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>
        <div class="update-user-container">
            <h2>ユーザー情報更新</h2>
            <form action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                <label for="name">名前:</label>
                <input type="text" id="name" name="name" value=<?php echo $_SESSION["name"] ?> required>

                <label for="password">新しいパスワード:</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">パスワード確認:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">更新</button>
                <?php if (isset($_SESSION["err_update_message"])) {
                    echo "<p style='color: red;'>" . $_SESSION["err_update_message"] . "</p>";
                } ?>
            </form>
        </div>
    <?php } ?>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
        <?php

        include "sql_connection.php";

        // フォームからの入力を取得
        $name = $_POST["name"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            if ($password === $confirm_password) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET name=?, password=? WHERE id=?");
                $stmt->bind_param("ssi", $name, $hashed_password, $_SESSION["id"]);
                $stmt->execute();
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $_SESSION["id"]);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                // ログイン成功時にindex.phpにリダイレクト
                $_SESSION["id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["err_update_message"] = null;

                header("Location: index.php");
                exit;
            } else {
                $_SESSION["err_update_message"] = "確認と違うパスワードが入力されました。";
                header("Location: update_profile.php");
                exit;
            }
        } else {
            // ログイン失敗
            $_SESSION["err_update_message"] = "そのemailは登録されています。";
            header("Location: update_profile.php");
            exit;
        }

        $conn->close();
        ?>
    <?php } ?>
</body>

</html>