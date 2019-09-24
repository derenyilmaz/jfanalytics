<?php
    include "JotForm.php";
    $jotformAPI = new JotForm();

    if(isset($_POST['submit'])){
        if((empty($_POST['user'])) or (empty($_POST['pass']))){
            echo 'username or password cannot be empty';
        }
        else{
            $username = $_POST['user'];
            $password = $_POST['pass'];
            $credentials = array(
                "username" => $username,
                "password" => $password,
                "appName" => "analytics",
                "access" => 'full',
            );
            $response = $jotformAPI->loginUser($credentials);
            if($response){
                $appkey = $response['appKey'];
                session_start();
                $_SESSION['key'] = $appkey;
                $_SESSION['usr'] = $username;
                header('location: forms.php');
            }
            else{
                echo 'noo';
            }
        }
    }
?>
<html>
<head>
    <title> login pls</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="skeleton.css">

</head>
<body>
    <!--<br><br><br><br><br><br><br><br><br>
    <div class="four columns"><p></p></div>
    <form method="post">
        <div class="row ">
            username: <input type="text" name="user">
            password: <input type="password" name="pass">
            <button class="button" id="username" name="submit">login</button>
        </div>
    </form>-->
    <center>
      <h3>log in with your jotform credentials</h3>
      <br>
      <br>
      <form method="post">
        <table>
          <tr>
            <td>
              <b>username:</b>
            </td>
            <td>
              <input type='text' name="user">
            </td>
          </tr>
          <tr>
            <td>
              <b>password:</b>
            </td>
            <td>
              <input type='password' name="pass">
            </td>
          </tr>
          <tr>
            <td></td>
            <td>
              <button id='username' name="submit" class="u-pull-right"> submit </button>
            </td>
          </tr>
        </table>
      </form>

</body>
</html>
