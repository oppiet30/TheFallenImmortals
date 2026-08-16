<?php
$active = time();
$setactive = $conn->prepare("UPDATE characters SET lastactive=? WHERE id=?");
$setactive->bind_param("ii", $active, $_SESSION['userid']);
$setactive->execute();

?>