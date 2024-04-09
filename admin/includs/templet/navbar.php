<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="dashboard.php"><?php echo lang ("HOME_ADMIN") ?></a>
        </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav">
      <li><a href="cateogris.php"><?php echo lang ('CATEGORIES') ?></a></li>
      <li><a href="items.php"><?php echo lang ('ITEMS') ?></a></li>
      <li><a href="members.php"><?php echo lang('MEMBERS') ?></a></li>
      <li><a href="#"><?php echo lang ('STATISTICS') ?></a></li>
      <li><a href="#"><?php echo lang ('LOGS') ?></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
        <?php 
    // Check if 'Username' key is set in $_SESSION array
    if (isset($_SESSION['Username'])) {
        echo $_SESSION['Username'];
    } else {
        // Handle the case where 'Username' is not set, such as displaying a default value or redirecting the user to a login page
        echo 'Guest'; // or any default value you prefer
        // Alternatively, you can redirect the user to a login page
        // header("Location: login.php");
        // exit();
    }
?>
<span class="caret"></span>:

</a>

          <ul class="dropdown-menu">
          <li><a href="members.php?do=Edit&ID=<?php echo isset($_SESSION['ID']) ? $_SESSION['ID'] : ''; ?>">edit profile</a></li>
          <li><a href="../index.php">Visit Shop</a></li>
            <li><a href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
            <li><a href="#">settings</a></li>
            <li><a href="logout.php">logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
