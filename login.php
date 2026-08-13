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

$getchar = $conn->prepare("SELECT * FROM characters WHERE username=?");
$getchar->bind_param("s", $username);
$getchar->execute();
$char = $getchar->get_result()->fetch_assoc();

$loginMatched = false;
$oldFormatUpgraded = false;
$matchedViaTemp = false;

if($char){
	if(password_verify($plaintextPassword, $char['password'])){
		$loginMatched = true;
	}elseif(murder($plaintextPassword) === $char['password']){
		$loginMatched = true;
		$newHash = password_hash($plaintextPassword, PASSWORD_DEFAULT);
		$upd = $conn->prepare("UPDATE characters SET password=? WHERE id=?");
		$upd->bind_param("si", $newHash, $char['id']);
		$upd->execute();
		$char['password'] = $newHash;
	}elseif(md5($plaintextPassword) === $char['password']){
		$oldFormatUpgraded = true;
		$newHash = password_hash($plaintextPassword, PASSWORD_DEFAULT);
		$upd = $conn->prepare("UPDATE characters SET password=? WHERE username=?");
		$upd->bind_param("ss", $newHash, $char['username']);
		$addNewPassword = $upd->execute();
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
	$findonline = $conn->prepare("SELECT * FROM characters WHERE lastactive>? AND username=?");
	$findonline->bind_param("is", $time, $char['username']);
	$findonline->execute();
	$active = $findonline->get_result()->fetch_assoc();

	if($active != NULL){
		print("alert('You are already logged in. Try coming back in ten minutes. If this problem persist contact the administrator at Alex.jezior(at)gmail.com');");
		die();
	}
    if(isset($_SESSION['userid'])){
        $getchar = $conn->prepare("SELECT * FROM characters WHERE id=?");
        $getchar->bind_param("i", $_SESSION['userid']);
        $getchar->execute();
        $char = $getchar->get_result()->fetch_assoc();
    }
    $getbanned = $conn->prepare("SELECT * FROM banned WHERE ip=?");
    $getbanned->bind_param("s", $char['ip']);
    $getbanned->execute();
    $bannedResult = $getbanned->get_result();
    if($bannedResult->num_rows == "1")
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
				$resetup = $conn->prepare("UPDATE characters SET password=?, temppass='None' WHERE id=?");
				$resetup->bind_param("si", $newHash, $char['id']);
				$resetup->execute();
				print("alert('Please change your password in the Edit Account link at the top of the page! Your current password is the temporary password.');");
			}


            if($char['logins'] == "0"){
            	$messagechat = "<strong><font color=\'#FFAA00\'>".$char['username']." has entered The Fallen Immortals for the first time! Welcome; You can <a href=\'Javascript: viewVote();\'>vote</a> in the link above as labeled to get some gold to get your character started! Check out the <a href=\'Javascript: viewFAQ();\'>FAQ</a> if you get stuck!</font></strong><br />";
            	$insChat = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, 'Chatroom')");
            	$insChat->bind_param("iss", $date, $char['username'], $messagechat);
            	$insChat->execute();
            }
            $addLogin = $conn->prepare("UPDATE characters SET logins=logins+1 WHERE id=?");
            $addLogin->bind_param("i", $char['id']);
            $addLogin->execute();

            if($char['dailylogin'] == "0" && $char['level'] != "1"){
				$messagechat = "<strong>Thank you for logging in today!<b><u><br/><br/>CHAT AND GAME RULES:</u></b></br>-No foul language in the chat.</br>-No cheat of any kind.(Example: Macros, game bugs exploits)</br>-ALL bugs found must be reported to the Forum page.</br>-Staff members always make the right decision. This means DO NOT argue with them.</br>-Please don\'t talk about other games here. If you would like to advertise here please contact the username Ajezior.<br /><br />Thanks for reading over these guidelines and I wish you a great day.</strong><br />";
            	$insChat = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, ?)");
            	$insChat->bind_param("isss", $date, $char['username'], $messagechat, $char['username']);
            	$insChat->execute();
            	$randomlogin = rand(1,4);
            	if($randomlogin == "1"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchLevel = rand(1,30);
            		}else{
            			$muchLevel = rand(1,1000);
            		}
            		$update = $conn->prepare("UPDATE characters SET level=level+? WHERE username=?");
            		$update->bind_param("is", $muchLevel, $char['username']);
            		$update->execute();
            		$inform = "<b>You gain ".number_format($muchLevel)." Levels for logging in today!</b><br />";
            	}elseif($randomlogin == "2"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchgold = rand(1,20000);
            		}else{
            			$muchgold = rand(1,5000000);
            		}
            		$update = $conn->prepare("UPDATE characters SET gold=gold+? WHERE username=?");
            		$update->bind_param("is", $muchgold, $char['username']);
            		$update->execute();
            		$inform = "<b>You gain ".number_format($muchgold)." Gold for logging in today!</b><br />";
            	}elseif($randomlogin == "3"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchstats = rand(1,20);
            		}else{
            			$muchstats = rand(1,200);
            		}
            		$update = $conn->prepare("UPDATE characters SET stats=stats+? WHERE username=?");
            		$update->bind_param("is", $muchstats, $char['username']);
            		$update->execute();
            		$inform = "<b>You gain ".number_format($muchstats)." Stat Points for logging in today!</b><br />";
            	}elseif($randomlogin == "4"){
            		$smallbig = rand(1,20);
            		if($smallbig < 20){
            			$muchblood = rand(1,500);
            		}else{
            			$muchblood = rand(1,5000);
            		}
            		$update = $conn->prepare("UPDATE characters SET blood=blood+? WHERE username=?");
            		$update->bind_param("is", $muchblood, $char['username']);
            		$update->execute();
            		$inform = "<b>You gain ".number_format($muchblood)." Blood for logging in today!</b><br />";
            	}
            	$insChat2 = $conn->prepare("INSERT INTO chatroom (`date`, `userlevel`, `username`, `message`, `to`) VALUES (?, '3', ?, ?, ?)");
            	$insChat2->bind_param("isss", $date, $char['username'], $inform, $char['username']);
            	$insChat2->execute();
            	$addDailyLogin = $conn->prepare("UPDATE characters SET dailylogin='1' WHERE username=?");
            	$addDailyLogin->bind_param("s", $char['username']);
            	$addDailyLogin->execute();

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
