<?php
require("../../config.php");
require("../lib.php");
require("../MngDb.php");

$request=new MngDb();
if(isset($_POST['name1']) && isset($_POST['name2']))
{
		$logSrc=$_POST['name1'];
		$logDest=$_POST['name2'];
		
		//retrieves source id
		$res=$request->execRequest("SELECT UID FROM account WHERE id='".Secure_text($logSrc)."'",'id : '.$logSrc.'not found');
		$row = mysqli_fetch_assoc($res);
		$srcUid= $row["UID"];
		
		//retrieves destination id
		$res=$request->execRequest("SELECT UID FROM account WHERE id='".Secure_text($logDest)."'",'id : '.$logDest.'not found');
		$row = mysqli_fetch_assoc($res);
		$destUid= $row["UID"];
		
		
	
// todo :put a loop there
		 $copy_mode="warehouse";
		 switch ($copy_mode) {
			case "warehouse":
			//supressing destination warehouse
			$request->execRequest("DELETE FROM `pangya_item_warehouse` WHERE UID='".$destUid."'",'delete fails for destination id : '.$destUid);
			//copy warehouse from source to destination user 
			$statement="INSERT INTO pangya_item_warehouse (UID,typeid,valid,regdate,Gift_flag,flag,Applytime,EndDate,C0,C1,C2,C3,C4,Purchase,ItemType,ClubSet_WorkShop_Flag,ClubSet_WorkShop_C0,ClubSet_WorkShop_C1,ClubSet_WorkShop_C2,ClubSet_WorkShop_C3,ClubSet_WorkShop_C4,Mastery_Pts,Recovery_Pts,Level,Up,Total_Mastery_Pts,Mastery_Gasto) 
						(
						SELECT '".$destUid."',typeid,valid,regdate,Gift_flag,flag,Applytime,EndDate,C0,C1,C2,C3,C4,Purchase,ItemType,ClubSet_WorkShop_Flag,ClubSet_WorkShop_C0,ClubSet_WorkShop_C1,ClubSet_WorkShop_C2,ClubSet_WorkShop_C3,ClubSet_WorkShop_C4,Mastery_Pts,Recovery_Pts,Level,Up,Total_Mastery_Pts,Mastery_Gasto
						FROM pangya_item_warehouse as w1
						WHERE UID=".$srcUid." and NOT EXISTS(SELECT typeid
															 FROM pangya_item_warehouse as w2
															 WHERE w1.typeid = w2.typeid)
						)";
			$request->execRequest($statement,'warehouse copy fails');		

				break;
			case "club":
				//supressing destination club
				$request->execRequest("DELETE FROM `pangya_clubset_enchant` WHERE UID='".$destUid."'",'delete fails for destination id : '.$destUid);
				//copy club configuration from source to destination user 
				$statement="INSERT INTO pangya_clubset_enchant(UID,item_id,pang,c0,c1,c2,c3,c4)
							(	
								SELECT ".$destUid.",wa.id,c.pang,c.c0,c.c1,c.c2,c.c3,c.c4
								FROM pangya_clubset_enchant as c
								INNER JOIN (
									SELECT w1.item_id as item_id,w2.item_id as id
									FROM pangya_item_warehouse as w1
									INNER JOIN pangya_item_warehouse as w2
										ON ((w2.UID=".$destUid." and w1.UID=".$srcUid.") and (w1.typeid=w2.typeid))  			
									) as wa ON c.item_id=wa.item_id and c.uid=".$srcUid."
							)";
				$res=$request->execRequest($statement,'card copy fails');
				break;
			case "card":
				//supressing destination card
				$request->execRequest("DELETE FROM `pangya_card` WHERE UID='".$destUid."'",'delete fails for destination id : '.$destUid);
				$res=$request->execRequest($statement,'club configuration copy fails');
				//copy card from source to destination user 
				$statement="INSERT INTO pangya_card(UID,card_typeid,QNTD,GET_DT,USE_DT,END_DT,Slot,Efeito,Efeito_Qntd,card_type,USE_YN)
							(	
								SELECT ".$destUid.",card_typeid,QNTD,GET_DT,USE_DT,END_DT,Slot,Efeito,Efeito_Qntd,card_type,USE_YN
								FROM pangya_card as c1
								WHERE UID=".$srcUid." and NOT EXISTS(SELECT typeid
															 FROM card_typeid as c2
															 WHERE c1.typeid = c2.typeid)
							)";
				$res=$request->execRequest($statement,'card copy fails');
			case "character":
				//supressing destination character
				$request->execRequest("DELETE FROM pangya_character_information WHERE UID='".$destUid."'",'delete fails for destination id : '.$destUid);
				//copy character from source to destination user 
				$statement="INSERT INTO pangya_character_information(typeid,UID,parts_1,parts_2,parts_3,parts_4,parts_5,parts_6,parts_7,parts_8,parts_9,parts_10,parts_11,parts_12,parts_13,parts_14,parts_15,parts_16,parts_17,parts_18,parts_19,parts_20,parts_21,parts_22,parts_23,parts_24,default_hair,default_shirts,gift_flag,PCL0,PCL1,PCL2,PCL3,PCL4,Purchase,auxparts_1,auxparts_2,auxparts_3,auxparts_4,auxparts_5,CutIn,Mastery)
							(	
								SELECT typeid,".$destUid.",parts_1,parts_2,parts_3,parts_4,parts_5,parts_6,parts_7,parts_8,parts_9,parts_10,parts_11,parts_12,parts_13,parts_14,parts_15,parts_16,parts_17,parts_18,parts_19,parts_20,parts_21,parts_22,parts_23,parts_24,default_hair,default_shirts,gift_flag,PCL0,PCL1,PCL2,PCL3,PCL4,Purchase,auxparts_1,auxparts_2,auxparts_3,auxparts_4,auxparts_5,CutIn,Mastery
								FROM pangya_character_information as c1
								WHERE UID=".$srcUid." and NOT EXISTS(SELECT typeid
															 FROM pangya_character_information as c2
															 WHERE c1.typeid = c2.typeid)
							)";
				
				$request->execRequest($statement,'character copy fails');
				$statement=	"SELECT MAX(item_id) as id
							FROM pangya_character_information
							WHERE UID=".$destUid."
							";
				$res=$request->execRequest($statement,'character copy fails');
				$row = mysqli_fetch_assoc($res);
				$maxId = $row["id"];
				$statement=	"UPDATE pangya_user_equip 
							 SET character_id='".$maxId."'
							 WHERE UID=".$destUid;
				$request->execRequest($statement,'character copy fails');
			break;
		}
		
		
		$request->close();
		
			
		echo 'Congratulation copy done!';		
}
else{
	die('<div class="formstatuserror">You need write all</div>');
}
?>