<?php
include('indexdb.php');

                $actionStart = mysqli_query($conn, "SELECT * FROM announcements ORDER BY id DESC");
				$data = "";
                while($announcement = mysqli_fetch_array($actionStart)){

                $data .= "".$announcement['announcement']."<br /><br />";

				}


print("fillDiv('displayArea','".$data."');");
?>