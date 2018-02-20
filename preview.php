<!doctype html>
<html>
	<head>
		<meta charset="utf8" />
		<meta name="author" content="Keombre" />
		<meta name="author" content="Wochozka" />
		<title>Náhled vlastního kánonu před odesláním</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	</head>

	<body>
	<?php
		$jmeno=$_POST["jmeno"];
		$prijmeni=$_POST["prijmeni"];
		$trida=$_POST["trida"];
		$knihy=$_POST["knihy"];	
	?>
	<p>
	Jméno: <?php echo($jmeno . " " . $prijmeni . ", " . $trida . "<br /> \n"); ?>
	<br />
	Kánon:
	<br />
	<?php
		echo(str_replace("\n","<br />",$knihy));

	?>
	<p>
	Chcete-li si vytisknout nebo uložit kopii Vašeho kánonu, učiňte tak na této stránce.<br />
	</p>
	<p>
	--------------- SERVICE ------------------
	<br /><br /><br />
	<?php
		## rozdeleni promenne do pole (parsovani knih po radcich) ##
		$array = explode("\n", $knihy);

		print_r($array);

		## cyklus pro kontrolu duplicity ##
		for($a=0;$a<=20;$a++){
			if($a==0){
				if(isset($array[$a])){
					echo(trim(substr($array[$a], 4, 3)) . "\n");
				}					
			}
			else{
				if(isset($array[$a])){
					echo(trim(substr($array[$a], 1, 3)) . "\n");
				}
			}
		}

		## cyklus pro kontrolu obdobi knih : nutny vzorec AABBBCCCCDDDDD (2xA, 3xB, 4xC a 5xD) ##
		for($a=0;$a<=20;$a++){
			if($a==0){
				echo(substr($array[$a], 0, 4));
			}
			else{
				echo(substr($array[$a], 0, 1));				
			}
		}
		
		## potvrzeni spravnosti aby se zobrazilo tlacitko aktivni ##
		$allok = "true";
	?>
	<br /><br /><br />
	------------- EOF SERVICE ----------------
	</p>
	<form method="POST" action="send.php"> 
	<input type="hidden" name="jmeno" value="<?echo $jmeno?>" />
	<input type="hidden" name="prijmeni" value="<?echo $prijmeni?>" />
	<input type="hidden" name="trida" value="<?echo $trida?>" />
	<input type="hidden" name="knihy" value="<?echo $knihy?>" />
	<?php if(isset($_POST["allok"]) && $_POST["allok"]=="true"):?>
		<input type=\"submit\" value=\"Odeslat seznam ke zpracování\" />
	<?php else: ?>
		<h2 style=\"color:red;\">Formulář nelze odeslat, protože nesplňuje parametry:</h2>
		Světová a česká literatura do konce 18. století (min. 2 díla)<br />
		Světová a česká literatura 19. století (min. 3 díla)<br />
		Světová literatura 20. a 21. století (min. 4 díla)<br />
		Česká literatura 20. a 21. století (min. 5 děl)<br />
		<br />
		<input type=\"submit\" disabled value=\"Odeslat seznam ke zpracování\" />
	<?php endif;?>
	
	</p>
	</body>
</html>
