<?php
$active = time();
$setactive = mysqli_query("UPDATE characters SET lastactive='".$active."' WHERE id='".$_SESSION['userid']."'");

?>