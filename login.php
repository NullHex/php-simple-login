<?php
session_start();

$env = parse_ini_file('.env');
$GLOBALS['db_host'] = $env["DB_HOST"];
$GLOBALS['db_name'] = $env["DB_NAME"];
$GLOBALS['db_user'] = $env["DB_USER"];
$GLOBALS['db_pass'] = $env["DB_PASS"];

function check_credentials($input_username, $input_password){
    mysqli_report(MYSQLI_REPORT_STRICT);$LOGEEDIN = false;
    try {$conn = new mysqli($GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);} catch (mysqli_sql_exception $e) {return;}
    $stmt = $conn->prepare("SELECT * FROM admin where username = ?");
    $stmt->execute([$input_username]);$result = $stmt->get_result();
    if($result->num_rows ==  1) {
        $database_row = $result->fetch_assoc();
        if($database_row['password'] == hash('sha256', $input_password)){$_SESSION["admin_session"] = 1;}
    }$stmt->close();$conn->close();
    header("Location: ./login.php?error");die();
}

//-------------------------------------login section
if(isset($_POST["username"]) && isset($_POST["password"])){check_credentials($_POST["username"], $_POST["password"]);}
//-------------------------------------

//-------------------------------------logout section
if(isset($_GET["logout"])){unset($_SESSION["admin_session"]);}
//-------------------------------------

//-------------------------------------check if logged redirect to panel
if(isset($_SESSION["admin_session"])){header("Location: ./controlpanel.php");die();}
//-------------------------------------


//-------------------------------------message section
$message ="<span class='message'>&#8203;</span>";
if(isset($_GET["logout"])){$message = "<span class='message c-g'>Logged Out Successfully</span>";}
elseif(isset($_GET["error"])){$message = "<span class='message c-r'>Wrong Username or Password</span>";}
//-------------------------------------


?>
<html lang='en' >
    <head>
        <meta charset='UTF-8'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login - ADMIN</title>
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet'><link rel='stylesheet' href='./style.css'>
    </head>
    <body class='align'>
        <div class='grid'>
            <form action='' method='POST' class='form login'>
                <h1>LOGIN</h1>
                <?=$message;?>
            <div class='form__field'>
                <label for='login__username'><svg class='icon'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='#user'></use></svg><span class='hidden'>Username</span></label>
                <input id='login__username' type='text' name='username' class='form__input' placeholder='Username' required>
            </div>
            <div class='form__field'>
                <label for='login__password'><svg class='icon'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='#lock'></use></svg><span class='hidden'>Password</span></label>
                <input id='login__password' type='password' name='password' class='form__input' placeholder='Password' required>
            </div>
            <div class='form__field'>
                <input type='submit' value='Sign In'>
            </div>
            </form>
        </div>
        <svg xmlns='http://www.w3.org/2000/svg' class='icons'><symbol id='arrow-right' viewBox='0 0 1792 1792'><path d='M1600 960q0 54-37 91l-651 651q-39 37-91 37-51 0-90-37l-75-75q-38-38-38-91t38-91l293-293H245q-52 0-84.5-37.5T128 1024V896q0-53 32.5-90.5T245 768h704L656 474q-38-36-38-90t38-90l75-75q38-38 90-38 53 0 91 38l651 651q37 35 37 90z'/></symbol><symbol id='lock' viewBox='0 0 1792 1792'><path d='M640 768h512V576q0-106-75-181t-181-75-181 75-75 181v192zm832 96v576q0 40-28 68t-68 28H416q-40 0-68-28t-28-68V864q0-40 28-68t68-28h32V576q0-184 132-316t316-132 316 132 132 316v192h32q40 0 68 28t28 68z'/></symbol><symbol id='user' viewBox='0 0 1792 1792'><path d='M1600 1405q0 120-73 189.5t-194 69.5H459q-121 0-194-69.5T192 1405q0-53 3.5-103.5t14-109T236 1084t43-97.5 62-81 85.5-53.5T538 832q9 0 42 21.5t74.5 48 108 48T896 971t133.5-21.5 108-48 74.5-48 42-21.5q61 0 111.5 20t85.5 53.5 62 81 43 97.5 26.5 108.5 14 109 3.5 103.5zm-320-893q0 159-112.5 271.5T896 896 624.5 783.5 512 512t112.5-271.5T896 128t271.5 112.5T1280 512z'/></symbol></svg>
        <script>document.getElementsByTagName("input")[0].focus();</script>
    </body>
</html>

