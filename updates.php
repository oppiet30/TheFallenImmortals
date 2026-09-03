<?php
include('indexdb.php');

                $actionStart = db_query("SELECT * FROM announcements ORDER BY id DESC");
				$data = "";
                while($announcement = db_fetch_array($actionStart)){

                $data .= "".$announcement['announcement']."<br /><br />";

				}


print("fillDiv('displayArea','".$data."');");
?>