<?php 
session_name("icsession");
session_start();
include('db.php');

$getchar = mysqli_query("SELECT * FROM characters WHERE id='".$_SESSION['userid']."'") or die(mysqli_error());
$char = mysqli_fetch_assoc($getchar);
$display .= "<center><select id=\'enemylist\'>";

            $getenemies = mysqli_query("SELECT * FROM enemies ORDER BY level");

            while($enemies = mysqli_fetch_array($getenemies))

            {

                if($char['enemyid'] == $enemies['id'])

                {

                    $display .= "<option value=\'".$enemies['id']."\' selected=\'selected\'>".$enemies['name']." (".$enemies['level'].")</option>";

                }

                else

                {

                    $display .= "<option value=\'".$enemies['id']."\'>".$enemies['name']." (".$enemies['level'].")</option>";

                }

            }

            if($char['security'] == "1"){

			    $top = mt_rand(1,400);

			    $left = mt_rand(1,400);

			    $display .= "</select> Run away button!!! Go catch it! <input type=\'button\' value=\'Fight\' onclick=\'this.disabled=true;this.value=\"Submitting...\";fightEnemy();\' style=\'position:absolute;top:".$top."px;left:".$left."px;\' /></center><br /><br />";

			}else{

			    $display .= "</select> <input type=\'button\' value=\'Fight\' onclick=\'this.disabled=true;this.value=\"Submitting...\";fightEnemy();\' /></center><br /><br />";

			}

            print("fillDiv('displayArea','".$display."');");

?>