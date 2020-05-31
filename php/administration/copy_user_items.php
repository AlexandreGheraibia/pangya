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
		if (mysqli_num_rows($res) > 0) 
		{
			$row = mysqli_fetch_assoc($res);
			$srcUid= $row["UID"];
		}
		//retrieves destination id
		$res=$request->execRequest("SELECT UID FROM account WHERE id='".Secure_text($logDest)."'",'id : '.$logDest.'not found');
		if (mysqli_num_rows($res) > 0) 
		{
			$row = mysqli_fetch_assoc($res);
			$destUid= $row["UID"];
		}
		/**************
		 * supressing *
		 **************/
		//destination warehouse
		$request->execRequest("DELETE FROM `pangya_item_warehouse` WHERE UID='".$destUid."'",'delete fails for destination id : '.$destUid);
		//destination club
		$request->execRequest("DELETE FROM `pangya_clubset_enchant` WHERE UID='".$destUid."'",'delete fails for destination id : '.$destUid);
		
		/*******************************************
		 *copy from source user to destination user*
		 *******************************************/
		//warehouse 
		$statement="INSERT INTO pangya_item_warehouse (UID,typeid,valid,regdate,Gift_flag,flag,Applytime,EndDate,C0,C1,C2,C3,C4,Purchase,ItemType,ClubSet_WorkShop_Flag,ClubSet_WorkShop_C0,ClubSet_WorkShop_C1,ClubSet_WorkShop_C2,ClubSet_WorkShop_C3,ClubSet_WorkShop_C4,Mastery_Pts,Recovery_Pts,Level,Up,Total_Mastery_Pts,Mastery_Gasto) 
					(SELECT '".$destUid."',typeid,valid,regdate,Gift_flag,flag,Applytime,EndDate,C0,C1,C2,C3,C4,Purchase,ItemType,ClubSet_WorkShop_Flag,ClubSet_WorkShop_C0,ClubSet_WorkShop_C1,ClubSet_WorkShop_C2,ClubSet_WorkShop_C3,ClubSet_WorkShop_C4,Mastery_Pts,Recovery_Pts,Level,Up,Total_Mastery_Pts,Mastery_Gasto FROM pangya_item_warehouse WHERE UID=".$srcUid.")";
		$request->execRequest($statement,'warehouse copy fails');		
		//club configuration
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
		$res=$request->execRequest($statement,'club configuration copy fails');
		$request->close();
		
			
		echo 'Congratulation copy done!';		
}
else{
	die('<div class="formstatuserror">You need write all</div>');
}
?>