<!doctype html>
<html>
	<head>
		<meta charset="utf8" />
		<meta name="author" content="Keombre" />
		<meta name="author" content="Wochozka" />
		<title>Náhled vlastního kánonu před odesláním</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<style>
		@media print
			{    
				.no-print, .no-print *
				{
					display: none !important;
				}
			}
		</style>
	</head>

	<body>
	<nav class="navbar navbar-inverse no-print">
      <div class="container">
        <div class="navbar-header">
		  <a class="navbar-brand" href="index.php">GPJP Maturitní kánon</a>
		</div>
	  </div>
	</nav>
	<form method="POST" action="send.php"> 
	<input type="hidden" name="jmeno" value="<?echo $jmeno?>" />
	<input type="hidden" name="prijmeni" value="<?echo $prijmeni?>" />
	<input type="hidden" name="trida" value="<?echo $trida?>" />
	<input type="hidden" name="knihy" value="<?echo $knihy?>" />

	<div class="container">
	<div class="row">
		<div class="col-sm-3 no-print"></div>
		<div class="col-sm-6 no-print">
		<div class="well">
	<?php
		$jmeno=$_POST["jmeno"];
		$prijmeni=$_POST["prijmeni"];
		$trida=["oktavaa" => "Oktáva A", "oktavab" => "Oktáva B", "4c" => "4. C"][$_POST["trida"]];
		$knihy=$_POST["knihy"];

		## rozdeleni promenne do pole (parsovani knih po radcich) ##
		$array = explode("\n", $knihy);

		## kontrola duplicity a počtu
		if(count($array) == count(array_unique($array))){
			$dups = "ok";
		}
		if(count($array)==21){
			echo "<div style='cursor: pointer;' class='alert alert-danger'><strong>Nedostatek knih v kánonu!</strong></div>";
		} else {

		## cyklus pro kontrolu obdobi knih : nutny vzorec AABBBCCCCDDDDD (2xA, 3xB, 4xC a 5xD) ## dodelat vypocet (kolikrat se co vyskytuje), dela pismenne retezce
		for($a=0;$a<=20;$a++){
			if($a==0){
				$pattern = substr($array[$a], 0, 4);
			}
			else{
				$pattern = $pattern . substr(@$array[$a], 0, 1);				
			}
		}
		foreach(count_chars($pattern, 1) as $i => $val){
			if(chr($i)=="A" and $val >= 2){$countA="ok";}
			if(chr($i)=="B" and $val >= 3){$countB="ok";}
			if(chr($i)=="C" and $val >= 4){$countC="ok";}
			if(chr($i)=="D" and $val >= 5){$countD="ok";}
		}

		## potvrzeni spravnosti aby se zobrazilo tlacitko aktivni ##
		if(@$countA=="ok" and @$countB=="ok" and @$countC=="ok" and @$countD=="ok"){
			$seasons = "true";
		}

		if(@$dups=="ok" && @$seasons=="true"){
			echo("<div class='alert alert-success'>Vše se zdá být v pořádku!</div>");
			echo("<input type='submit' class='btn btn-success' style='margin-top: 20px;' value='Odeslat ke zpracování' />");
		}
		else{
			echo("<div class='alert alert-danger'>Kánon nelze odeslat, protože nesplňuje parametry</div>\n");
			
			if(@$dups != "ok"){
				echo("<strong class='text-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> Seznam obsahuje duplicitní hodnoty!</strong><br />");
			}

			if(@$countA=="ok"){
				echo("<span class='text-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ");
			}
			else{
				echo("<strong class='text-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> ");
			}
			echo("Světová a česká literatura do konce 18. století (min. 2 díla)<br />");
			if(@$countA=="ok"){
				echo("</span>");
			}
			else{
				echo("</strong>");
			}
			
			if(@$countB=="ok"){
				echo("<span class='text-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ");
			}
			else{
				echo("<strong class='text-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> ");
			}
			echo("Světová a česká literatura 19. století (min. 3 díla)<br />");
			if(@$countB=="ok"){
				echo("</span>");
			}
			else{
				echo("</strong>");
			}
			
			if(@$countC=="ok"){
				echo("<span class='text-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ");
			}
			else{
				echo("<strong class='text-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> ");
			}
			echo("Světová literatura 20. a 21. století (min. 4 díla)<br />");
			if(@$countC=="ok"){
				echo("</span>");
			}
			else{
				echo("</strong>");
			}


			if(@$countD=="ok"){
				echo("<span class='text-success'><span class='glyphicon glyphicon-ok' aria-hidden='true'></span> ");
			}
			else{
				echo("<strong class='text-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span> ");
			}
			echo("Česká literatura 20. a 21. století (min. 5 děl)<br />");
			if(@$countD=="ok"){
				echo("</span>");
			}
			else{
				echo("</strong>");
			}

			echo("<input type='submit' style='margin-top: 20px;' class='btn btn-danger disabled' disabled value='Nelze odeslat ke zpracování' />");
		}
	}

	?>
	<button class="pull-right no-print btn btn-primary" style="margin-top: 20px;" onclick="window.print(); return false;">Tisk</button>
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
	</div>
	</form>
	</body>
</html>
