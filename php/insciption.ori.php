<?php
require("../config.php");
require("./lib.php");

/*
// Original Proc

// ServerUID = 0

	INSERT INTO account(ID, `PASSWORD`, LastLogonTime, RegDate, NICK, UserName, UserIp, ServerID, LogonCount)
	VALUES(userID, Pass, NOW(), NOW(), userID, userId, IPaddr, ServerUID, 1);

    SET IDUSER = @@identity; 
    
    INSERT INTO user_info(UID) VALUES(IDUSER);

	INSERT INTO pangya_last_players_user(UID) VALUES(IDUSER);

	INSERT INTO trofel_stat(UID) VALUES(IDUSER);

	INSERT INTO tutorial(UID) VALUES(IDUSER);

	INSERT INTO pangya_user_macro(UID) VALUES(IDUSER);

	INSERT INTO pangya_attendance_reward(UID) VALUES(IDUSER);

	INSERT INTO pangya_scratchy_prob_sec(UID) VALUES(IDUSER);

	INSERT INTO black_papel_prob_sec(UID) VALUES(IDUSER);

	INSERT INTO pangya_tiki_points(UID) VALUES(IDUSER);

	INSERT INTO pangya_myroom(UID) VALUES(IDUSER);

	INSERT INTO pangya_dolfini_locker(UID) VALUES(IDUSER);
    
    INSERT INTO pangya_assistente(UID) VALUES(IDUSER);
    
    INSERT INTO pangya_grand_zodiac_pontos(UID) VALUES(IDUSER);
    
    INSERT INTO pangya_daily_quest_player(UID, LAST_QUEST_ACCEPT, TODAY_QUEST) VALUES(IDUSER, NULL, NULL);

	INSERT INTO td_room_data(UID, TYPEID, POS_X, POS_Y, POS_Z, POS_R) VALUES(IDUSER, 1207986208, 15.2, 0, 12.5, 152);

    SELECT UID INTO IDUSER FROM account WHERE ID = id_in;
    
    INSERT INTO contas_beta(UID, NOME, SOBRE_NOME, EMAIL) VALUE(IDUSER, nome_in, sobre_nome_in, email_in);
    
    INSERT INTO pangya_player_ip(UID, IP) VALUES(IDUSER, ip_in);
*/


// Debut de la page
	
if(isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['email']) )
{
		$Login 	 = Secure_text($_POST['name']);
		$Pass 	 = Secure_text($_POST['pass']);
		$email 	 = Secure_text(trim($_POST['email']));

		if(isset($_POST['gm']))
		{
			$GM = 1;
		}
		else
		{
			$GM = 0;
		}
		
		$mysqli = mysqli_connect(MYSQL_IP, MYSQL_USER, MYSQL_PASSWORD, MYSQL_TABLE);
		if (mysqli_connect_errno($mysqli)) {
			echo "Error Mysqli : " . mysqli_connect_error();
		}
		else
		{
			
			$Requette = "SELECT * FROM account WHERE id='".$Login."'";
			$res = mysqli_query($mysqli, $Requette);
			if (mysqli_num_rows($res) > 0) 
			{
				die('<div class="formstatuserror">Pseudo In Use ... Try other </div>');
			}
			

			//On crypte le mot de passe
			$Password = md5($Pass);
			
			$Date = date('Y-m-d H:i:s');

			// Creation de l'utilistateur
			if( $GM == 1)
			{
				$Requette = "INSERT INTO account(ID, `PASSWORD`, LastLogonTime, RegDate, NICK, UserName, UserIp, ServerID, LogonCount, capability)
				VALUES('".$Login."', '".$Password."', '".$Date."', '".$Date."', '".$userid."', '".$userid."', '127.0.0.1', 0, 1 , ".CAPABILITY.");";
			}
			else
			{
				
				$Requette = "INSERT INTO account(ID, `PASSWORD`, LastLogonTime, RegDate, NICK, UserName, UserIp, ServerID, LogonCount)
				VALUES('".$Login."', '".$Password."', '".$Date."', '".$Date."', '".$userid."', '".$userid."', '127.0.0.1', 0, 1);";
			}
			$res = mysqli_query($mysqli, $Requette);
			
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 001</div>');
			}
			
			// List Max ID
			$Requette = "SELECT * FROM account WHERE id='".$Login."'";
			$res = mysqli_query($mysqli, $Requette);
			
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 002</div>');
			}
			
			// On recuper l'user ID
			if (mysqli_num_rows($res) > 0) 
			{
				$row = mysqli_fetch_assoc($res);
				$maxID = $row["UID"];
			} 
			$userid = $maxID;


			// User info
			$Requette = "INSERT INTO user_info(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 003</div>');
			}
			
			//Pangya last player
			$Requette = "INSERT INTO pangya_last_players_user(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 004</div>');
			}
			
			//Pangya Trophée
			$Requette = "INSERT INTO trofel_stat(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 005</div>');
			}
			
			//Pangya tutorial
			$Requette = "INSERT INTO tutorial(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 006</div>');
			}
			
			//Pangya macro
			$Requette = "INSERT INTO pangya_user_macro(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 007</div>');
			}
			
			//Pangya Récompense
			$Requette = "INSERT INTO pangya_attendance_reward(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 008</div>');
			}
			
			//Pangya Scratchy
			$Requette = "INSERT INTO pangya_scratchy_prob_sec(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 010</div>');
			}
			
			//Pangya Black pappel
			$Requette = "INSERT INTO black_papel_prob_sec(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 011</div>');
			}
			
			//Pangya Tiki
			$Requette = "INSERT INTO pangya_tiki_points(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 012</div>');
			}
			
			//Pangya Room
			$Requette = "INSERT INTO pangya_myroom(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 013</div>');
			}
			
			//Pangya Casier
			$Requette = "INSERT INTO pangya_dolfini_locker(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 014</div>');
			}
			
			//Pangya Assistant
			$Requette = "INSERT INTO pangya_assistente(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 015</div>');
			}
			
			//Pangya Grand Zodiac
			$Requette = "INSERT INTO pangya_grand_zodiac_pontos(UID) VALUES('".$userid."')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 016</div>');
			}

			//Pangya Daily Quest
			$Requette = "INSERT INTO pangya_daily_quest_player(UID, LAST_QUEST_ACCEPT, TODAY_QUEST) VALUES('".$userid."', NULL, NULL)";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 017</div>');
			}
			
			//Pangya Data Room
			// i dont know why 1207986208 ... 
			// And Pos X/Z/R too sorry
			$Requette = "INSERT INTO td_room_data(UID, TYPEID, POS_X, POS_Y, POS_Z, POS_R) 
			VALUES('".$userid."', 1207986208, 15.2, 0, 12.5, 152);";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 018</div>');
			}
			
			//Pangya - Insert In beta BDD - idk why
			$Requette = "INSERT INTO contas_beta(UID, NOME, SOBRE_NOME, EMAIL)
			VALUE('".$userid."', 'Register', 'ByWeb', '".$email."');";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 019</div>');
			}
			
			//Pangya Log IP ? NOP sorry i set local ip because , i don't care
			$Requette = "INSERT INTO pangya_player_ip(UID, IP) VALUES('".$userid."', '127.0.0.1')";
			$res = mysqli_query($mysqli, $Requette);
			if(!$res && ERROR_USER > 0)
			{
				die("Mysql Error :".mysqli_error($mysqli));
			}
			else if(!$res && ERROR_USER == 0)
			{
				die('<div class="formstatuserror">Error : 020</div>');
			}
					
			mysqli_close($mysqli);

			echo 'OK';
		}
}
else
{
		die('<div class="formstatuserror">You need write all</div>');
}

 

?>