<?php

/*
==========================================
==members page
==add|edite|delete members
=========================================
*/

  
session_start();
$pageTitle = 'members';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    if ($do == 'manage') {    
        
        $query = '';

        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {

            $query = 'AND RegStatus = 0';

        }

        $stmt=$con->prepare("SELECT * FROM users WHERE GroupID !=1  $query ORDER BY ID Desc ");
        $stmt->execute();
        $rows =$stmt->fetchAll();


        ?>
        <h1 class="text-center" >Manage member</h1>;
        <div class="containar">
            <div class="table-responsive">
<table class="main-table text-center table table-bordered ">
        <tr>
                             <td>#ID</td>
							<td>Avatar</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registered Date</td>
							<td>Control</td>
        </tr>
        <?php
        
        foreach($rows as $row) {
            echo "<tr>";
                echo "<td>" . $row['ID'] . "</td>";
                echo "<td>";
                if (empty($row['avatar'])) {
                    echo 'No Image';
                } else {
                    echo "<img src='uploads/avatars/" . $row['avatar'] . "' alt='' />";
                }
                echo "</td>";

                echo "<td>" . $row['Username'] . "</td>";
                echo "<td>" . $row['Email'] . "</td>";
                echo "<td>" . $row['FullName'] . "</td>";
                echo "<td>" . $row['Date'] ."</td>";
                echo "<td>
                <a href='members.php?do=Edit&id=" . $row['ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                <a href='members.php?do=Delete&id=" . $row['ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                    if ($row['RegStautus'] == 0) {
                        echo "<a 
                                href='members.php?do=Activate&id=" . $row['ID'] . "' 
                                class='btn btn-info activate'>
                                <i class='fa fa-check'></i> Activate</a>";
                    }
                echo "</td>";
            echo "</tr>";
        }
        
        ?>
        <tr>
      

        
</table>
            </div>
        <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus" ></i> Add New member</a>


        </div>

   <?php } elseif ($do == 'Add') {          ?>
        <h1 class="text-center" >Add New member</h1>;
            <div class="containar">
            <form class="form-horizontal" action="?do=Insert" method="POST">
            <div class="form-group">
    <label class="col-sm-2 control-label">Username:</label>
    <div class="col-sm-10 col-md-2">
        <input type="text" name="username" class="form-control" autocomplete="off" placeholder="Enter your username" required />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Password:</label>
    <div class="col-sm-10 col-md-2">
        <input type="password" name="password" class=" password form-control" autocomplete="new-password" placeholder="Enter your password (required)" required />
        <i class="show-pass fa fa-eye fa-2x"></i>

    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Email:</label>
    <div class="col-sm-10 col-md-2">
        <input type="email" name="email" class="form-control" placeholder="Enter your email" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-2 control-label">Fullname:</label>
    <div class="col-sm-10 col-md-2">
        <input type="text" name="full" class="form-control" placeholder="Enter your fullname" />
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-2-10 col-md-4">
        <input type="submit" value="Add member" class="btn btn-primary btn-lg" />
    </div>
</div>
<div class="col-sm-10 col-md-6">
							<input type="file" name="avatar" class="form-control" required="required" />
						</div>
					 
            </form>
            </div>
<?php ;
    }elseif($do=='Insert'){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo" <h1 class='text-center'> Update member</h1> ";
echo"<div calass='container'>";


$avatarName = $_FILES['avatar']['name'];
$avatarSize = $_FILES['avatar']['size'];
$avatarTmp	= $_FILES['avatar']['tmp_name'];
$avatarType = $_FILES['avatar']['type'];

// List Of Allowed File Typed To Upload

$avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");

// Get Avatar Extension

$avatarExtension = strtolower(end(explode('.', $avatarName)));




         $user   = $_POST['username'];
         $pass   =sha1( $_POST['password']);
         $email  = $_POST['email'];
         $name   = $_POST['full'];
      
         $pass = '';
         
     
         $formErros=array();
     
     if(strlen($user)<3){
     
     $formErros[]='Username must be more than 5';
     
     
     }
     
         if(empty($user)){
     
     $formErros[]='username cant be empty';      
     
         }
         if(empty($name)){
     
             $formErros[]='fullname cant be empty';
             
                 }
                 if(empty($email)){
     
                     $formErros[]='email cant be empty';
                     
                         }
                      
                 foreach($formErros as $error ){
     
                        echo $error . '<br/>';
                        
                                    }        
                        
                                    if(empty($formErros)) {
                                        $check = checkItem("Username", "users", $user);
                                        if($check == 1) {
                                            $theMsg='<div class="alert alert-denger">Sorry this user already exists</div>';
                                            redirectHome($theMsg,'back');
                                        } else {
                                            try {
                                                $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName,RegStautus,Date) VALUES (:zuser, :zpass, :zmail, :zname,1,now()),zavatar");
                                                $stmt->execute(array(
                                                    'zuser' => $user,
                                                    'zpass' => $pass,
                                                    'zmail' => $email,
                                                    'zname' => $name,
                                                    'zavatar'	=> $avatar

                                                ));
                                                echo 'User inserted successfully';
                                            } catch (PDOException $e) {
                                                if ($e->getCode() == '23000') {
                                                    echo 'Sorry, this user already exists';
                                                } else {
                                                    
                                                    echo 'Error: ' . $e->getMessage();
                                                    redirectHome($theMsg,'back',4);

                                                }
                                            }
                                        }
                                    }
     
         
     
     
                                    $theMsg="<div class='alert alert-success'>". $stmt->rowCount() . 'Record Updated</div>';
        
     }
     
     
          else{
     
         $errorMsg='SORRY YOU CANT '; 
         redirectHome($errorMsg,10000   )
     ;}
     
     

    } elseif ($do == 'Edit') {

        // Check If Get Request userid Is Numeric & Get Its Integer Value

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

        // Select All Data Depend On This ID

        $stmt = $con->prepare("SELECT * FROM users WHERE ID = ? LIMIT 1");

        // Execute Query

        $stmt->execute(array($id));

        // Fetch The Data

        $row = $stmt->fetch();

        // The Row Count

        $count = $stmt->rowCount();

        // If There's Such ID Show The Form

        if ($count > 0) { ?>

            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>" />
                    <!-- Start Username Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required" />
                        </div>
                    </div>
                    <!-- End Username Field -->
                    <!-- Start Password Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                        <input type="hidden" name="oldpassword" value="<?php echo isset($row['Password']) ? $row['Password'] : ''; ?>" />
                            <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Dont Want To Change" />
                        </div>
                    </div>
                    <!-- End Password Field -->
                    <!-- Start Email Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required" />
                        </div>
                    </div>
                    <!-- End Email Field -->
                    <!-- Start Full Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="full" value="<?php echo $row['FullName'] ?>" class="form-control" required="required" />
                        </div>
                    </div>
                    <!-- End Full Name Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            </div>

        <?php

        // If There's No Such ID Show Error Message

        } else {

            echo "<div class='container'>";

            $theMsg = '<div class="alert alert-danger">Theres No Such ID</div>';

            redirectHome($theMsg);

            echo "</div>";

        }
}elseif($do=='Update'){

   echo" <h1 class='text-center'> Update member</h1> ";
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Id     = $_POST['id'];
    $user   = $_POST['username'];
    $email  = $_POST['email'];
    $name   = $_POST['full'];
 
    $pass = '';
    if(empty($_POST['newpassword'])) {
        $pass = $_POST['oldpassword'];
    } else {
        $pass = sha1($_POST['newpassword']);
    }

    $formErros=array();

if(strlen($user)<3){

$formErros[]='Username must be more than 5';


}

    if(empty($user)){

$formErros[]='username cant be empty';

    }
    if(empty($name)){

        $formErros[]='fullname cant be empty';
        
            }
            if(empty($email)){

                $formErros[]='email cant be empty';
                
                    }
                 
            foreach($formErros as $error ){

echo $error . '<br/>';

            }        
 
if(empty($formErros)){

    $stmt = $con->prepare("UPDATE users SET Username=?, email=?, fullname=?, password=? WHERE Id=?");

    $stmt->execute(array($user, $email, $name, $pass, $Id));



}

    $stmt = $con->prepare("UPDATE users SET Username=?, email=?, fullname=?, password=? WHERE Id=?");

    $stmt->execute(array($user, $email, $name, $pass, $Id));

    $theMsg="<div class='alert alert-success'>". $stmt->rowCount() . 'Record Updated</div>';
    redirectHome($theMsg,'back');
}


     else{

    $theMsg='<div class="alert alert-danger">SORRY YOU CANT </div>'; 
     redirectHome($theMsg,'back');

}

}elseif ($do == 'delete') {
    $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;


    $stmt = $con->prepare("SELECT * FROM users WHERE ID=? LIMIT 1");

    $stmt->execute(array($ID));

    $count = $stmt->rowCount();
    if ($stmt->rowCount() > 0) {

        $stmt = $con->prepare("DELETE FROM users WHERE ID= :zuser");
        $stmt->bindParam(":zuser", $ID);
        $stmt->execute();
        $theMsg= "<div class='alert alert-success'>" . $stmt->rowCount() . 'Record DELETED</div>';
        redirectHome($theMsg);
    }else{
        
        $theMsg='<div class="alert alert-danger">this id not exist </div>';   
              redirectHome($theMsg);

    }

}elseif ($do == 'Activate') {

			echo "<h1 class='text-center'>Activate Member</h1>";
			echo "<div class='container'>";

				// Check If Get Request userid Is Numeric & Get The Integer Value Of It

				$Id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('ID', 'users', $Id);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE users SET RegStautus = 1 WHERE ID = ?");

					$stmt->execute(array($Id));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div>';

					redirectHome($theMsg);

				}
            
            
            }else{
                $theMsg = '<div class="alert alert-danger">This ID is Not Exist</div>';

                redirectHome($theMsg);

            }



include "includs/templet/footer.php";

}
        else {
header('Location:index.php');
exit();        


      }    ;


?>