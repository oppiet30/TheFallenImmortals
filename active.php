<?php
$active = time();
$setactive = mysqli_query($login, "UPDATE characters SET lastactive='".$active."' WHERE id='".$_SESSION['userid']."'");

?>