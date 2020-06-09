<?php
	// Criado em 02/01/2016 por Acrisio
	
	include "../config/config.inc";
	include "../config/MysqlManager.inc";
	$atual_dir = getcwd();
	//$fopDebug = fopen($atual_dir."\\DebugAlex.log", "w");
	
	$db = new MysqlManager($con_dados);
	$params = new paramsArr();
	$uccuid = (isset($_POST['selfDesignUID'])) ? $_POST['selfDesignUID'] : 0;
	$item_id = (isset($_POST['selfDesignItemID'])) ? $_POST['selfDesignItemID'] : 0;
	$key = (isset($_POST['selfDesignKey'])) ? $_POST['selfDesignKey'] : "";

	$query = "CALL ProcCheckSecurityKey(?, ?, ?)";
	//fwrite($fopDebug, "uccuid:");
	//fwrite($fopDebug, $uccuid);
	//fwrite($fopDebug, "item_id:");
	//fwrite($fopDebug, $item_id);
	//fwrite($fopDebug, "key:");
	//fwrite($fopDebug, $key);
	$params->clear();
	$params->add('i', $uccuid);
	$params->add('i', $item_id);
	$params->add('s', $key);
	//	fwrite($fopDebug, "ici 1\n");
	if ($result = $db->execPreparedStmt($query, $params->get())) {
	 //  fwrite($fopDebug, "ici 2\n");
		$row = $result->fetch_assoc();
	//	fwrite($fopDebug, "row:");
	//	fwrite($fopDebug, $row['UID']);
		if(!empty($row['UID']) && $uccuid != 0 && $uccuid == $row['UID']){
			$selfDesignName=$_FILES['selfDesignFileName']['name'];
		//	 fwrite($fopDebug, "selfDesignName\n");
		//	 fwrite($fopDebug,$selfDesignName);
			$start=strrpos ( $selfDesignName,"_");
			$end=strrpos ( $selfDesignName,".");
			$UCCIDX = substr($selfDesignName,$start+1,$end-$start-1);
		//	 fwrite($fopDebug, "UCCIDX:");
		//	 fwrite($fopDebug, $UCCIDX);
			$query = "SELECT UID FROM TU_UCC WHERE UID = ? AND UCCIDX = ?";

			$params->clear();
			$params->add('i', $uccuid);
			$params->add('s', $UCCIDX);

			if ($result = $db->execPreparedStmt($query, $params->get())) {
				//fwrite($fopDebug, "ici 4\n");
				$row = $result->fetch_assoc();

				if(!empty($row['UID']) && $uccuid != 0 && $uccuid == $row['UID']){
					$atual_dir = getcwd();
				       // fwrite($fopDebug, "ici 5\n");
					chdir("../");

					$dir = getcwd();

					$dir_self = "\\_Files\\SelfDesign\\";

					if(isset($_POST['selfDesignFolder'])){
						$dir .= $dir_self.$_POST['selfDesignFolder']."\\";

						if(!is_dir($dir))
							mkdir($dir);

						$dir .= $_FILES['selfDesignFileName']['name'];

						move_uploaded_file($_FILES['selfDesignFileName']['tmp_name'], $dir);

						$fop = fopen($atual_dir."\\teste.txt", "w");

						 fwrite($fop, $dir);

						fclose($fop);

						// file_put_contents($atual_dir."\\Files.txt", serialize($_FILES));

						// file_put_contents($atual_dir."\\SD_log.txt", serialize($_POST));

						#PANGYA_UPLOAD_ERR UNKNOWN
						#PANGYA_SRVVAR     UNKNOWN
						#PANGYA_UPLOAD_OK  0

						echo "0";
					}else{
						//security breach here to solve it, we must invalidate
						//the security key from the database
						//or echo 0
						//for renew the key
						echo "3";
					}
				}else{
					echo "12"; # UCCIDX Errado
					//security breach
				}
			}else {
				echo "13"; # Erro no execPreparedStmt
				//security breach
			}
		}else{
			//security breach
			echo "1";
		}
	}else {
		echo "13"; # Erro no execPreparedStmt
	}
//	fclose($fopDebug);

?>
