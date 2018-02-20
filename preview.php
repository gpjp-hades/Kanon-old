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
	<div class="container">
	<div class="row">
		<div class="col-sm-5">
	<?php
		$jmeno=$_POST["jmeno"];
		$prijmeni=$_POST["prijmeni"];
		$trida=$_POST["trida"];
		$knihy=$_POST["knihy"];

		## rozdeleni promenne do pole (parsovani knih po radcich) ##
		$array = explode("\n", $knihy);

		## kontrola duplicity a počtu
		if(count($array) == count(array_unique($array))){
			$dups = "ok";
		}
		if(count($array)==21){
			$count = "ok";
		} else {
			echo "<div style='margin-top: 10px;' class='alert alert-danger'><strong>Nedostatek knih v kánonu!</strong></div>";
		}
	?>
	</div>
	</div>
	</div>
	<h3>Kánon 2018</h3>
	<div class="table-responsive">
		<table style="table">
			<tr>
				<td>Jméno:&nbsp;&nbsp;</td>
				<td><strong><?= $jmeno . " " . $prijmeni?></strong></td>
			</tr>
			<tr>
				<td>Třída: </td>
				<td><strong><?= $trida?></strong></td>
			</tr>
		</table>
	</div>
	<hr>
	<h4>Kánon</h4>
	<table class="table">
	<?php
		$books = explode("\n", $knihy);
		foreach($books as $book) {
			$data = explode(";", $book);
			if (count($data) == 2) {
				echo "<tr><td>$data[0]</td><td>$data[1]</td></tr>";
			}
		}
	?>
	</table>
	<hr>
	<span>
	Chcete-li si vytisknout nebo uložit kopii Vašeho kánonu, učiňte tak na této stránce.<br />
	</span>
	<?php
	
	if(count($array)!=21)
		exit();

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

	<form method="POST" action="send.php"> 
	<input type="hidden" name="jmeno" value="<?echo $jmeno?>" />
	<input type="hidden" name="prijmeni" value="<?echo $prijmeni?>" />
	<input type="hidden" name="trida" value="<?echo $trida?>" />
	<input type="hidden" name="knihy" value="<?echo $knihy?>" />
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
	
	</p>
	</body>
</html>
