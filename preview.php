<!doctype html>
<html>
	<head>
		<meta charset="utf8" />
<<<<<<< HEAD
		<title>Náhled vlastního kánonu před odesláním</title>
=======
		<meta name="author" content="Keombre" />
		<meta name="author" content="Wochozka" />
		<title>Náhled vlastního kánonu před odesláním</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
>>>>>>> 7e2143f7fccb6d402063c80612344a0a50f86a25
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
<<<<<<< HEAD

=======
	<p>
	--------------- SERVICE ------------------
	<br /><br /><br />
>>>>>>> 7e2143f7fccb6d402063c80612344a0a50f86a25
	<?php
		## rozdeleni promenne do pole (parsovani knih po radcich) ##
		$array = explode("\n", $knihy);

<<<<<<< HEAD
		## kontrola duplicity a počtu
		if(count($array) == count(array_unique($array))){
			$dups = "ok";
		}
		if(count($array)==21){
			$count = "ok";
		}
	


		## cyklus pro kontrolu obdobi knih : nutny vzorec AABBBCCCCDDDDD (2xA, 3xB, 4xC a 5xD) ## dodelat vypocet (kolikrat se co vyskytuje), dela pismenne retezce
		for($a=0;$a<=20;$a++){
			if($a==0){
				$pattern = substr($array[$a], 0, 4);
			}
			else{
				$pattern = $pattern . substr($array[$a], 0, 1);				
			}
		}
		foreach(count_chars($pattern, 1) as $i => $val){
			if(chr($i)=="A" and $val >= 2){$countA="ok";}
			if(chr($i)=="B" and $val >= 3){$countB="ok";}
			if(chr($i)=="C" and $val >= 4){$countC="ok";}
			if(chr($i)=="D" and $val >= 5){$countD="ok";}
		}

		## potvrzeni spravnosti aby se zobrazilo tlacitko aktivni ##
		if($countA=="ok" and $countB=="ok" and $countC=="ok" and $countD=="ok"){
			$seasons = "true";
		}
	?>

=======
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
>>>>>>> 7e2143f7fccb6d402063c80612344a0a50f86a25
	<form method="POST" action="send.php"> 
	<input type="hidden" name="jmeno" value="<?echo $jmeno?>" />
	<input type="hidden" name="prijmeni" value="<?echo $prijmeni?>" />
	<input type="hidden" name="trida" value="<?echo $trida?>" />
	<input type="hidden" name="knihy" value="<?echo $knihy?>" />
<<<<<<< HEAD
	<?php

		if($count != "ok"){
			echo("<p style=\"color:red;\">Počet knih v kánonu je " . (count($array) - 1) . ", není tedy roven 20.<br />");
			echo("Kánon lze odeslat i s jiným počtem knih, případně výjimečně nepracuje správně kontrola počtu knih.<br />");
			echo("Ověřte, prosím, že Vámi odesílaný seznam, tak jak ho vidíte, je správný a odešlete.</p>");
		}
		if($dups=="ok" and $seasons=="true"){
			echo("<p style=\"color:green;\">Vše se zdá být v pořádku :-)</p>");
			echo("<input type=\"submit\" value=\"Odeslat seznam ke zpracování\" />");
		}
		else{
			echo("<h2 style=\"color:red;\">Kánon nelze odeslat, protože nesplňuje parametry:</h2>\n");
			
			if($dups != "ok"){
				echo("<p style=\"color:red;\">Seznam obsahuje duplicitní hodnoty!</p>");
			}

			if($countA=="ok"){
				echo("<span style=\"color:green;\">");
			}
			else{
				echo("<span style=\"color:red;\">");
			}
			echo("Světová a česká literatura do konce 18. století (min. 2 díla)</span><br />\n");
			
			if($countB=="ok"){
				echo("<span style=\"color:green;\">");
			}
			else{
				echo("<span style=\"color:red;\">");
			}
			echo("Světová a česká literatura 19. století (min. 3 díla)</span><br />\n");
			
			if($countC=="ok"){
				echo("<span style=\"color:green;\">");
			}
			else{
				echo("<span style=\"color:red;\">");
			}
			echo("Světová literatura 20. a 21. století (min. 4 díla)</span><br />\n");

			if($countD=="ok"){
				echo("<span style=\"color:green;\">");
			}
			else{
				echo("<span style=\"color:red;\">");
			}
			echo("Česká literatura 20. a 21. století (min. 5 děl)<br />\n");

				echo("<br />\n");
			echo("<input type=\"submit\" disabled value=\"Odeslat seznam ke zpracování\" />");			
		}
	?>
=======
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
>>>>>>> 7e2143f7fccb6d402063c80612344a0a50f86a25
	
	</p>
	</body>
</html>
