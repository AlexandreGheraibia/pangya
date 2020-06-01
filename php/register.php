<?php
require("./lib.php");
require("../config.php");
require("./MngUser.php");
require("./MngDb.php");


if(isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['email']) )
{
		$user=new MngUser(0,Secure_text($_POST['name']),Secure_text($_POST['pass']), Secure_text(trim($_POST['email'])));
		$user->setGm(isset($_POST['gm']));
		$request=new MngDb();
		$res=$request->execRequest("SELECT * FROM account WHERE id='".$user->login()."'",'last id not found');
		
		// The account already exist
		if (mysqli_num_rows($res) > 0) 
		{
			die('<div class="formstatuserror">Pseudo In Use ... Try other </div>');
		}
		
		// User creation
		$capability=($user->gm()?CAPABILITY:0);
		$statement = "INSERT INTO account(ID, PASSWORD, IDState, LastLogonTime, BlockTime, Logon, Sex, School, Event, MannerFlag, Event1, Event2, ChannelFlag, RegDate, NICK, UserName, UserIp, ServerID, LogonCount, capability, doTutorial)
			VALUES('".$user->login()."', '".$user->md5Pass()."',0, '".$user->dateToUse()."',0,0,0,0,0,0,0,0,0, '".$user->dateToUse()."', '".$user->id()."', '".$user->id()."', '".$_SERVER['REMOTE_ADDR']."', 0, 1 , '".$capability."', 1);";
		$request->execRequest($statement,'creation fail');
		
		// Retrieve user ID
		$statement = "SELECT * FROM account WHERE id='".$user->login()."'";
		$res=$request->execRequest($statement,'last id not found');
		if (mysqli_num_rows($res) > 0) 
		{
			$row = mysqli_fetch_assoc($res);
			$maxID = $row["UID"];
		} 
		$user->setId(intval($maxID));

		// User info
		$statement = "INSERT INTO user_info(UID,Xp,level,Pang,Cookie) VALUES('".$user->id()."',10000,255,1000000000,1000000000)";
		$request->execRequest($statement,'User info can\'t be set');
			
		//Pangya last player
		$statement = "INSERT INTO pangya_last_players_user(UID) VALUES('".$user->id()."')";
		$request->execRequest($statement,'Pangya last player can\'t be set');
			
		//Pangya trophy
		$statement = "INSERT INTO trofel_stat(UID) VALUES('".$user->id()."')";
		$request->execRequest($statement,'Pangya trophy can\'t be set');
	
		//Pangya tutorial
		$statement = "INSERT INTO tutorial(UID, Rookie, Beginner, Advancer) VALUES('".$user->id()."',10,10,10)";
		$request->execRequest($statement,'Pangya tutorial can\'t be set');
		
		//Pangya macro
		$statement = "INSERT INTO pangya_user_macro(UID, Macro1, Macro2, Macro3, Macro4, Macro5, Macro6, Macro7, Macro8, Macro9, Macro10) VALUES('".$user->id()."','Macro1','Macro2','Macro3','Macro4','Macro5','Macro6','Macro7','Macro8','Macro9','Macro10')";
		$request->execRequest($statement,'Pangya Macro can\'t be set');
	
		//Pangya Reward
		$statement = "INSERT INTO pangya_attendance_reward(UID, counter, item_typeid_now, item_qntd_now, item_typeid_after, item_qntd_after, login, reg_date) VALUES('".$user->id()."', 0, 0, 0, 0, 0, 0,'".$user->dateToUse()."')";
		$request->execRequest($statement,'Pangya Reward can\'t be set');
		
		//Pangya Scratchy
		$statement = "INSERT INTO pangya_scratchy_prob_sec(UID,scratchy_sec) VALUES('".$user->id()."', 1000)";
		$request->execRequest($statement,'Pangya Scratchy can\'t be set');
		
		//Pangya Black pappel
		$statement = "INSERT INTO black_papel_prob_sec(UID,probabilidade) VALUES('".$user->id()."', 1000)";
		$request->execRequest($statement,'//Pangya Black pappel can\'t be set');
		
		//Pangya Tiki
		$statement = "INSERT INTO pangya_tiki_points(UID, Tiki_Points, REG_DATE, MOD_DATE) VALUES('".$user->id()."', 0,'".$user->dateToUse()."','".$user->dateToUse()."')";
		$request->execRequest($statement,'Pangya Tiki can\'t be set');
	
		//Pangya Room
		$statement = "INSERT INTO pangya_myroom(UID, senha, public_lock, state) VALUES('".$user->id()."', NULL, 0, 0)";
		$request->execRequest($statement,'Pangya Room can\'t be set');
	
		//Pangya Casier
		$statement = "INSERT INTO pangya_dolfini_locker(UID, senha, pang, locker) VALUES('".$user->id()."', 0, 0, 0)";
		$request->execRequest($statement,'Pangya Room can\'t be set');

		//Pangya Assistant
		$statement = "INSERT INTO pangya_assistente(UID,Assist) VALUES('".$user->id()."', 0)";
		$request->execRequest($statement,'Pangya Assistant can\'t be set');

		//Pangya Grand Zodiac
		$statement = "INSERT INTO pangya_grand_zodiac_pontos(UID,pontos) VALUES('".$user->id()."', 1000)";
		$request->execRequest($statement,'Pangya Grand Zodiac can\'t be set');

		//Pangya Daily Quest
		$statement = "INSERT INTO pangya_daily_quest_player(UID, LAST_QUEST_ACCEPT, TODAY_QUEST) VALUES('".$user->id()."', NULL, NULL)";
		$request->execRequest($statement,'Pangya Daily Quest can\'t be set');
	
		//Pangya Data Room
		// i dont know why 1207986208 ... 
		// And Pos X/Z/R too sorry
		$statement = "INSERT INTO td_room_data(UID, TYPEID, POS_X, POS_Y, POS_Z, POS_R) 
		VALUES('".$user->id()."', 1207986208, 15.2, 0, 12.5, 152);";
		$request->execRequest($statement,'Pangya Data Room can\'t be set');
	
		//Pangya - Insert In beta BDD - idk why
		$statement = "INSERT INTO contas_beta(UID, NOME, SOBRE_NOME, EMAIL)
		VALUE('".$user->id()."', 'Register', 'ByWeb', '".$user->email()."')";
		$request->execRequest($statement,'Pangya - Insert In beta BDD can\'t be set');
	
		//Pangya Log IP ? NOP sorry i set local ip because , i don't care
		$statement = "INSERT INTO pangya_player_ip(UID, IP) VALUES('".$user->id()."', '".$_SERVER['REMOTE_ADDR']."')";
		$request->execRequest($statement,'Pangya - Insert In beta BDD can\'t be set');
		$request->close();
		echo 'OK';
}
else
{
		die('<div class="formstatuserror">You need write all</div>');
}

?>