<?php
include('indexdb.php');
session_name("fallenimmortals");
session_start();
// Legacy hash formats, kept only to verify (and then upgrade) passwords
// that predate the switch to password_hash()/PASSWORD_DEFAULT (bcrypt).
// Never use murder() to create a new hash - only to check an old one.
function murder($data){
	$salt = "'/0U'LL |\|3\/3R Ph19UR3 0U7 \/\/|-|@ 7|-|3 54L7 15. pLU5 \/\/|-|3R35 7|-|3 p3PP3R?";
	$salt = md5($salt);
	$data = md5($salt.$data);
	$data = base64_encode($data);
	$data = sha1($data);
	return $data;
}
$date = time();
$username = $_POST['userAlias'];
$plaintextPassword = $_POST['userPass'];

$getchar = mysqli_query($conn, "SELECT * FROM characters WHERE username='".$username."'");
$char = mysqli_fetch_assoc($getchar);

$loginMatched = false;
$oldFormatUpgraded = false;
$matchedViaTemp = false;

if($char){
	if(password_verify($plaintextPassword, $char['password'])){
		$loginMatched = true;
	}elseif(murder($plaintextPassword) === $char['password']){
		$loginMatched = true;
		$newHash = password_hash($plaintextPassword, PASSWORD_DEFAULT);
		mysqli_query($conn, "UPDATE characters SET password='".$newHash."' WHERE id='".$char['id']."'");
		$char['password'] = $newHash;
	}elseif(md5($plaintextPassword) === $char['password']){
		$oldFormatUpgraded = true;
		$newHash = password_hash($plaintextPassword, PASSWORD_DEFAULT);
		$addNewPassword = mysqli_query($conn, "UPDATE characters SET password='".$newHash."' WHERE username='".$char['username']."'");
	}elseif($char['temppass'] != "None" && (password_verify($plaintextPassword, $char['temppass']) || murder($plaintextPassword) === $char['temppass'])){
		$loginMatched = true;
		$matchedViaTemp = true;
	}
}

if($oldFormatUpgraded)
{
	$updatePass = "Since your last visit password security just got better!<br /><br /> Please login again!";
	print("fillDiv('displayArea','".$updatePass."');");
}
elseif($loginMatched)
{
	$time = time() - "700";
    $findonline = mysqli_query($conn, "SELECT * FROM characters WHERE lastactive>'".$time."' AND username='".$char['username']."'");
	$active = mysqli_fetch_assoc($findonline);
	
	if($active != NULL){
		print("alert('You are already logged in. Try coming back in ten minutes. If this problem persist contact the administrator at Alex.jezior(at)gmail.com');");
		die();
	}
    if(isset($_SESSION['userid'])){
        $getchar = mysqli_query($conn, "SELECT * FROM characters WHERE id='".$_SESSION['userid']."'");
        $char = mysqli_fetch_assoc($getchar);
    }
    $getbanned = mysqli_query($conn, "SELECT * FROM banned WHERE ip='".$char['ip']."'");
    if(mysqli_num_rows($getbanned) == "1")
    {
        print("alert('You are banned.');");
        print("window.location = 'http://www.thefallenimmortals.com/';");
    }
    else
    {
        if($char['activated'] == "Yes" || $char['level'] < "100")
        {
            $_SESSION['userid'] = $char['id'];
            include('varset.php');
        
			if($matchedViaTemp){
				$newHash = password_hash($plaintextPassword, PASSWORD_DEFAULT);
				$resetup = mysqli_query($conn, "UPDATE characters SET password='".$newHash."', temppass='None' WHERE id='".$char['id']."'");
				print("alert('Please change your password in the Edit Account link at the top of the page! Your current password is the temporary password.');");
			}
            
            
            if($char['logins'] == "0"){
            	$messagechat = "<strong><font color=\'#FFAA00\'>".$char['username']." has entered The Fallen Immortals for the first time! Welcome; You can <a href=\'Javascript: viewVote();\'>vote</a> in the link above as labeled to get some gold to get your character started! Check out the <a href=\'Javascript: viewFAQ();\'>FAQ</a> if you get stuck!</font></strong><br />";
    $query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '3', '".$char['username']."', '".$messagechat."', 'Chatroom')");
            }
            $addLogin = mysqli_query($conn, "UPDATE characters SET logins=logins+'1' WHERE id='".$char['id']."'");
            
            if($char['dailylogin'] == "0" && $char['level'] != "1"){
				$messagechat = "<strong>Thank you for logging in today!<b><u><br/><br/>CHAT AND GAME RULES:</u></b></br>-No foul language in the chat.</br>-No cheat of any kind.(Example: Macros, game bugs exploits)</br>-ALL bugs found must be reported to the Forum page.</br>-Staff members always make the right decision. This means DO NOT argue with them.</br>-Please don\'t talk about other games here. If you would like to advertise here please contact the username Ajezior.<br /><br />Thanks for reading over these guidelines and I wish you a great day.</strong><br />";
            	$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '3', '".$char['username']."', '".$messagechat."', '".$char['username']."')");
            	$randomlogin = rand(1,4);
            	if($randomlogin == "1"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchLevel = rand(1,30);
            		}else{
            			$muchLevel = rand(1,1000);
            		}
            		$update = mysqli_query($conn, "UPDATE characters SET level=level+'".$muchLevel."' WHERE username='".$char['username']."'");
            		$inform = "<b>You gain ".number_format($muchLevel)." Levels for logging in today!</b><br />";
            	}elseif($randomlogin == "2"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchgold = rand(1,20000);
            		}else{
            			$muchgold = rand(1,5000000);
            		}
            		$update = mysqli_query($conn, "UPDATE characters SET gold=gold+'".$muchgold."' WHERE username='".$char['username']."'");
            		$inform = "<b>You gain ".number_format($muchgold)." Gold for logging in today!</b><br />";
            	}elseif($randomlogin == "3"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchstats = rand(1,20);
            		}else{
            			$muchstats = rand(1,200);
            		}
            		$update = mysqli_query($conn, "UPDATE characters SET stats=stats+'".$muchstats."' WHERE username='".$char['username']."'");
            		$inform = "<b>You gain ".number_format($muchstats)." Stat Points for logging in today!</b><br />";
            	}elseif($randomlogin == "4"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchblood = rand(1,500);
            		}else{
            			$muchblood = rand(1,5000);
            		}
            		$update = mysqli_query($conn, "UPDATE characters SET blood=blood+'".$muchblood."' WHERE username='".$char['username']."'");
            		$inform = "<b>You gain ".number_format($muchblood)." Blood for logging in today!</b><br />";
            	}
            	$query = mysqli_query($conn, "INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES ('".$date."', '3', '".$char['username']."', '".$inform."', '".$char['username']."')");
            	$addDailyLogin = mysqli_query($conn, "UPDATE characters SET dailylogin='1' WHERE username='".$char['username']."'");
            	
            }

            print("window.location = 'game.php';");
        }
        else
        {
            print("fillDiv('displayArea','After level 100 your character must be activated. Follow the link in your email.');");
        }
    }
}
else
{
    print("fillDiv('displayArea','Incorrect Login Information.');");
}
?>