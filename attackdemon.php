<?php
session_name("icsession");
session_start();
require_once 'db.php';

$pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getCharacter($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM characters WHERE id = :userId");
    $stmt->execute(['userId' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getDemon($pdo, $demonId) {
    $stmt = $pdo->prepare("SELECT * FROM demons WHERE id = :demonId");
    $stmt->execute(['demonId' => $demonId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateDemonHealth($pdo, $demonId, $damage) {
    $stmt = $pdo->prepare("UPDATE demons SET health = health - :damage WHERE id = :demonId");
    $stmt->execute(['damage' => $damage, 'demonId' => $demonId]);
}

function updateCharacterStats($pdo, $userId, $field, $value) {
    $stmt = $pdo->prepare("UPDATE characters SET $field = $field + :value WHERE id = :userId");
    $stmt->execute(['value' => $value, 'userId' => $userId]);
}

function insertChatMessage($pdo, $date, $username, $message) {
    $stmt = $pdo->prepare("INSERT INTO chatroom (date, userlevel, username, message, `to`) VALUES (:date, 3, :username, :message, 'Chatroom')");
    $stmt->execute(['date' => $date, 'username' => $username, 'message' => $message]);
}

$char = getCharacter($pdo, $_SESSION['userid']);
$data = '';

if (isset($_POST['demonid'])) {
    $demon = getDemon($pdo, $_POST['demonid']);
    if ($demon) {
        if ($char['level'] >= 10000 && $demon['power'] == 1) {
            die("alert('After level 10,000, you may only kill Overlord Demons.');");
        }
        
        if ($demon['xpos'] != $char['posx'] || $demon['ypos'] != $char['posy']) {
            echo "alert('This demon is located at ({$demon['xpos']}, {$demon['ypos']}).');";
            die();
        }

        $demonRel = explode(', ', $demon['relativeLoc']);
        $charRel = explode(', ', $char['relativeLoc']);
        $demonXtop = $demonRel[0] + 32;
        $demonXbottom = $demonRel[0] - 32;
        $demonYtop = $demonRel[1] + 32;
        $demonYbottom = $demonRel[1] - 32;
        
        if (($demonXtop >= $charRel[0] && $demonXbottom <= $charRel[0]) && 
            ($demonYtop >= $charRel[1] && $demonYbottom <= $charRel[1])) {
            $characterChance = random_int(1, 3);
            if ($characterChance == 1 && $demon['health'] > 0) {
                $isMageClass = in_array($char['class'], ['Mage', 'Mage II', 'Mage III', 'Mage IV', 'Mage V', 'Sorcerer', 'Sorcerer II', 'Sorcerer III', 'Sorcerer IV', 'Sorcerer V', 'Elemental']);
                $damageMin = floor($isMageClass ? $char['int'] * 0.4 : $char['str'] * 0.4);
                $damageMax = floor($isMageClass ? $char['int'] * 0.5 : $char['str'] * 0.5);
                
                $damageVal = random_int($damageMin, $damageMax);
                $demonNewHealth = $demon['health'] - $damageVal;
                updateDemonHealth($pdo, $demon['id'], $damageVal);
                
                $data .= "You hit {$demon['name']} for " . number_format($damageVal) . " damage!<br />";
                $data .= "{$demon['name']} has " . number_format($demonNewHealth) . " left!<br />";
                
                if ($demonNewHealth <= 0) {
                    $data .= "You killed {$demon['name']}!<br />";
                    $rewardMessage = '';
                    switch ($demon['name']) {
                        case 'Barbatos':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'stats', 250);
                            $rewardMessage = "250 Stat Points";
                            break;
                        case 'Barbatos Overlord':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'stats', 1000);
                            $rewardMessage = "1,000 Stat Points";
                            break;
                        case 'Incubus':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'gold', 1500000);
                            $rewardMessage = "1,500,000 Gold";
                            break;
                        case 'Incubus Overlord':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'gold', 10000000);
                            $rewardMessage = "10,000,000 Gold";
                            break;
                        case 'Eurynome':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'blood', 300);
                            $rewardMessage = "300 oz. of Blood";
                            break;
                        case 'Eurynome Overlord':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'blood', 1200);
                            $rewardMessage = "1,200 oz. of Blood";
                            break;
                        case 'Gula':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'cash', 1);
                            $rewardMessage = "1 Cash";
                            break;
                        case 'Gula Overlord':
                            updateCharacterStats($pdo, $_SESSION['userid'], 'cash', 3);
                            $rewardMessage = "3 Cash";
                            break;
                    }
                    if ($rewardMessage) {
                        $data .= "You gain $rewardMessage in your success!<br />";
                        $chatMessage = "<strong><font color='#FF0000'>{$char['username']} killed {$demon['name']} and gained $rewardMessage!</font></strong><br />";
                        insertChatMessage($pdo, date('Y-m-d H:i:s'), $char['username'], $chatMessage);
                    }
                } else {
                    $demonDamageSmall = floor($demon['power'] * 500000);
                    $demonDamageBig = floor($demon['power'] * 5000000);
                    $demonDamage = random_int($demonDamageSmall, $demonDamageBig);
                    $data .= "{$demon['name']} hits you for " . number_format($demonDamage) . " damage!<br />";
                    $data .= "{$demon['name']} has " . number_format($demon['health']) . " left!<br />";
                    updateCharacterStats($pdo, $_SESSION['userid'], 'life', -$demonDamage);
                    $charHealth = $char['life'] - $demonDamage;
                    if ($charHealth <= 0) {
                        $data .= "<font color='#FF0000'>You have died, but the fierce will to defeat this demon has brought you back to life!</font><br />";
                        updateCharacterStats($pdo, $_SESSION['userid'], 'life', $char['endurance'] - $char['life']);
                    }
                }
            } elseif ($demon['health'] > 0) {
                $data .= "You fail to hit {$demon['name']}!<br />";
                $demonDamageSmall = floor($demon['power'] * 500000);
                $demonDamageBig = floor($demon['power'] * 5000000);
                $demonDamage = random_int($demonDamageSmall, $demonDamageBig);
                $data .= "{$demon['name']} hits you for " . number_format($demonDamage) . " damage!<br />";
                $data .= "{$demon['name']} has " . number_format($demon['health']) . " left!<br />";
                updateCharacterStats($pdo, $_SESSION['userid'], 'life', -$demonDamage);
                $charHealth = $char['life'] - $demonDamage;
                if ($charHealth <= 0) {
                    $data .= "<font color='#FF0000'>You have died, but the fierce will to defeat this demon has brought you back to life!</font><br />";
                    updateCharacterStats($pdo, $_SESSION['userid'], 'life', $char['endurance'] - $char['life']);
                }
            } else {
                $data .= "The demon is dead!<br />";
            }
        } else {
            die("alert('You must move closer to the demon to attack it!');");
        }
    } else {
        $data .= "The demon is dead!<br />";
    }
}

echo "fillDiv('travelDesc', '" . addslashes($data) . "');";
require_once 'updatestats.php';
?>
