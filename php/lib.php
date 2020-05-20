<?php
Function Secure_text($texte, $Numero = 0)
{
	if(is_array($texte))
	{
		$texte = implode($texte);
	}
	
	if(is_numeric($Numero) == true )
	{
		if($Numero == 1)
		{
			// Nettoyage a la dave :D ( Secu correcte mais un peu sec )
			$FinalText = Cut_HTML_JS_Inject($texte);
		}
		else if($Numero == 2)
		{
			// Nettoyage a la sauce PDO ( mal securisée à mon gout) ...
			$FinalText = htmlspecialchars($texte, ENT_QUOTES);
			$FinalText = addslashes($FinalText);
		}
		else if($Numero == 3)
		{
			//Nettoyage violent ! 
			//(Phrase - on supprime si c pas un chiffre ou des lettre ou un underscore ou un espace)
			$FinalText = preg_replace("#[^a-zA-Z0-9_ ]#", "", $texte);
		}
		else if($Numero == 4)
		{
			//On supprime si c pas des lettres (Mot - avec maj ou non mais pas de chiffre)
			$FinalText = preg_replace("#[^a-zA-Z]#", "", $texte);
		}
		else if($Numero == 5)
		{
			//On supprime si c pas des lettres (Mot - sans maj ni chiffre)
			$FinalText = preg_replace("#[^a-z]#", "", $texte);
		}
		else if($Numero == 6)
		{
			//On veux que des chiffre ma biche
			$FinalText = preg_replace("#[^0-9]#", "", $texte);
		}
		else
		{
			// Nettoyage a la dave si tu sais pas quoi choisir
			$FinalText = Cut_HTML_JS_Inject($texte);
		}
	}
	else
	{
		$FinalText = "tentative de hack ?!";
	}

	return $FinalText;
}

function Cut_HTML_JS_Inject($TextOriginal)
{
	$TextAfter = "";
	$FirstTurn = 0;

	while( $TextOriginal != $TextAfter)
	{
		if( $FirstTurn == 1 )
		{
			$TextOriginal = $TextAfter;
		}
		$Anti_Inject = array(
			"ACCOUNT_TBL" => "account",
			"odbc_exec" => " odbc__exec ",
			"mssql" => "ms-sql",
			"mysql" => "my-sql",
			"SiteODBC" => " ",
			"$" => " $ ",
			"<?php" => " ",
			"<?" => " ",
			"?>" => " ",
			"String.fromCharCode" => " ",
			"WHERE account=" => " ",
			"SET cash =" => " ",
			"</script>" => " ",
			"document.location.href" => " ",
			"<script>" => " ",
			"echo" => " ",
			"<iframe>" => " "
			);
		$TextAfter =  str_ireplace(array_keys($Anti_Inject), array_values($Anti_Inject), $TextOriginal);
		// On fini de tuer le HTML au cas ou <3
		$TextAfter = preg_replace ('/<[^>]*>/', ' ', $TextAfter); ; 
		// On netoye les double espace
		$TextAfter = trim(preg_replace('/ {2,}/', ' ', $TextAfter));
		
		// Premier tour cocote
		if( $FirstTurn == 0)
		{
			$FirstTurn = 1;
		}
	}
	
	return $TextOriginal;
}
?>