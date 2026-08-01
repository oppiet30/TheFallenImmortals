<?php
$active = time();
$setactive = mysqli_query($conn, "UPDATE characters SET lastactive='".$active."' WHERE id='".$_SESSION['userid']."'");

?>