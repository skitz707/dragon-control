<?php
require_once('Config.php');
function RenderCharacters($link, $userID){
  $qry = "SELECT * FROM CHARACTERS WHERE USERID='" . $userID . "'";
  $result = mysqli_query($link, $qry);

  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_array($result)){
      echo '<a class="dropdown-item" href="ChangeActiveCharacter?characterId='.$row['CharacterID'] . '">' . $row['CharacterName'] . '</a>';
    }
  }
}

function RenderAdminMenu($link){
  $isAdmin = $_SESSION['IsAdmin'];

  if($isAdmin == 1){
    echo '<li class="nav-item dropdown" id="AdminDropdown">';
    echo '<a class="nav-link dropdown-toggle" id="AdminMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>';
    echo '<div class="dropdown-menu">';
    echo '<a class="dropdown-item" href="Admin/Users/CreateUser">Create User</a>';
    echo '<a class="dropdown-item" href="Admin/Users">Users Admin</a>';
    echo '</div>';
  }
}
?>

<nav class="navbar bg-dark navbar-expand-sm navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="http://localhost/Sites/Dragon-Control">Home</a>
    </li>
    <li class="nav-item dropdown" id="AdminDropdown">
      <a class="nav-link dropdown-toggle" id="AdminMenuDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Characters</a>
      <div class="dropdown-menu">
        <?php RenderCharacters($link, $userID) ?>
      </div>
      <?php RenderAdminMenu($link, $userID) ?>

    </li>
  </ul>
</nav>
