<?php
$active = time();
$setactive = db_query("UPDATE characters SET lastactive=? WHERE id=?", [$active, $_SESSION['userid']]);

?>