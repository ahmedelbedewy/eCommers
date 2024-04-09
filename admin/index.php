<?php

session_start(); 
$noNavbar = '';
$pageTitle='Login';

if(isset($_SESSION['Username'])) {
    header('Location: dashboard.php');
    exit();
}

include 'init.php';
  
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedpass = sha1($password);

    $stmt = $con->prepare("SELECT
                        ID,  Username, password
                FROM
                        users
                WHERE
                        Username=?
                AND 
                        Password=?
                AND
                        GroupID=1
                LIMIT 1");

 

  $stmt->execute(array($username, $hashedpass));

    $row=$stmt->fetch();

    $count = $stmt->rowCount();

    if ($count >= 0) {
        $_SESSION['Username'] = $username;
        $_SESSION['ID']=$row['ID'];
        $_SESSION['FullName'] = $fullname;
        header('Location: dashboard.php');
        exit();
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-6">
            <form class="text-center login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <h4 class="text-center ">Admin login</h4>
                <input class="form-control input-lg" type="text" name="user" placeholder="Username" autocomplete="off"/>
                <input class="form-control input-lg" type="password" name="pass" placeholder="Password" autocomplete="new-password"/>
                <input class="btn btn-lg btn-primary btn-block" type="submit" value="login"/>
            </form>
        </div>
    </div>
</div>

<?php
include "includs/templet/footer.php";  
?>
