<!doctype html>
<html>
	<head>
		<meta charset="utf8" />
<<<<<<< HEAD
		<title>Zadání maturitního kánonu.</title>
	</head>

	<body>
	<?php
		if($_POST["button"]=="Zkontrolovat seznam"){
=======
		<meta name="author" content="Keombre" />
		<meta name="author" content="Wochozka" />
		<title>Zadání maturitního kánonu.</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<style>
		@media (min-width: 768px) {
			.my-buttons {
				margin-top: 100%;
			}
		}
		.my-buttons input {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		</style>
	</head>
	<body>

	<nav class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
		  <a class="navbar-brand" href="#">GPJP Maturitní kánon</a>
		</div>
	  </div>
	</nav>
  
	<?php
		if(@$_POST["button"]=="Dokončit seznam"){
>>>>>>> 7e2143f7fccb6d402063c80612344a0a50f86a25
			$action = "preview.php";
		}
		else {
			$action = "index.php";
		}
<<<<<<< HEAD
		if($_POST["jmeno"]){$jmeno=$_POST["jmeno"];}
		if($_POST["prijmeni"]){$prijmeni=$_POST["prijmeni"];}
	?>
	<form name="myform" action="<?php echo($action);?>" method="POST">
		<table>
			<tr><td>Jméno:</td><td>Příjmení:</td><td>Třída:</td></tr>
			<tr>
				<td><input type="text" name="jmeno" value="<?php echo($jmeno) ?>" /></td>
				<td><input type="text" name="prijmeni" value="<?php echo($prijmeni) ?>" /></td>
				<td>
					<select name="trida">
						<option value="oktavaa" <?php if($_POST["trida"]=="oktavaa"){echo("selected");}?>>Oktáva A</option>
						<option value="oktavab" <?php if($_POST["trida"]=="oktavab"){echo("selected");}?>>Oktáva B</option>
						<option value="4c" <?php if($_POST["trida"]=="4c"){echo("selected");}?>>4. C</option>
					</select>
				</td>
			</tr>
			<tr><td colspan="3">Školní kánon:</td></tr>
			<tr>
				<td colspan="2">
					<select name="kniha" style="width:510px;" size="10" ondblclick="submitform()">
					<?php
						//kod pro generator voleb typu <option value="xxx">xxx</option>
						$file = fopen("./kanon.csv", "r");
						echo($get_lines);
						for($a=0;$a<148;$a++) {
							$line = fgets($file);
							echo("<option value=\"$line\">$line</option>");
						}
						fclose($file)
					?>
					</select>
				</td>
				<td style="vertical-align:bottom;">
					<input type="submit" name="button" value="Přidat položku" /> <br /> <br /> <br /> <br /> <br /> <br />
					<input type="submit" name="button" value="Vyprázdnit" /><br /> <br />
					<input id="button_odeslat" type="submit" name="button" value="Zkontrolovat seznam" />
				</td>
			</tr>
</table>
<textarea name="knihy" cols="70" rows="25"/>
<?php
	if($_POST["button"] != "Vyprázdnit"){
		if($_POST["knihy"]){
			echo($_POST["knihy"]);
		}
		if($action == "index.php"){
			if($_POST["kniha"]){
				echo($_POST["kniha"]);
			}
		}
	}
?>
</textarea> <br>
	</form> 
	</body>
<?php
if($_POST["button"] == "Zkontrolovat seznam"){
=======
		if(@$_POST["jmeno"]){$jmeno=$_POST["jmeno"];}
		if(@$_POST["prijmeni"]){$prijmeni=$_POST["prijmeni"];}
	?>

  <form name="myform" action="<?php echo($action);?>" method="POST">

  <div class="container">
  <div class="row">
    <div class="col-sm-2">
		<div class="form-group">
			<label for="jmeno">Jméno:</label>
			<input type="text" name="jmeno" class="form-control" id="jmeno" value="<?php echo(@$jmeno) ?>" autofocus required />
		</div>
		<div class="form-group">
			<label for="prijmeni">Příjmení:</label>
			<input type="text" name="prijmeni" class="form-control" id="prijmeni" value="<?php echo(@$prijmeni) ?>" required />
		</div>
		<div class="form-group">
			<label for="trida">Třída:</label>
			<select name="trida" class="form-control" id="trida">
				<option disabled <?= isset($_POST['trida'])&&$_POST['trida']!=""?"":"selected"?>>Vyberte třídu</option>
				<option value="oktavaa" <?php if(@$_POST["trida"]=="oktavaa"){echo("selected");}?>>Oktáva A</option>
				<option value="oktavab" <?php if(@$_POST["trida"]=="oktavab"){echo("selected");}?>>Oktáva B</option>
				<option value="4c" <?php if(@$_POST["trida"]=="4c"){echo("selected");}?>>4. C</option>
			</select>
		</div>
    </div>
    <div class="col-sm-5">
		<div class="form-group">
		<label for="kniha">Školní kánon:</label>
		<select name="kniha" id="kniha" class="form-control" size="29" ondblclick="submitform()">
<?php
	$file = fopen("./kanon.csv", "r");
	echo(@$get_lines);
	for($a=0;$a<148;$a++) {
		$line = fgets($file);
		echo("<option value=\"$line\">$line</option>");
	}
	fclose($file)
?>
		</select>
		</div>
    </div>
	<div class="col-sm-2">
		<div class="my-buttons">
			<input type="submit" class="form-control btn btn-default" name="button" value="Přidat ►" />
			<input type="submit" class="form-control btn btn-danger" name="button" value="Vyprázdnit" />
		</div>
	</div>
    <div class="col-sm-3">
		<div class="form-group">
			<label for="knihy">Vybrané knihy:</label>
			<textarea style="resize: none; white-space: nowrap;  overflow: auto;" name="knihy" cols="70" rows="22" class="form-control" id="knihy">
<?php
		if(@$_POST["button"] != "Vyprázdnit") {
			if(@$_POST["knihy"]){
				echo($_POST["knihy"]);
			}
			if($action == "index.php"){
				if(@$_POST["kniha"]){
					echo($_POST["kniha"]);
				}
			}
		}
?></textarea>
		<input id="button_odeslat" class="form-control btn btn-success" style="margin-top: 15px" type="submit" name="button" value="Dokončit seznam" />
    </div>
  </div>
</div>
</form>
</body>
<?php
if(@$_POST["button"] == "Dokončit seznam"){
>>>>>>> 7e2143f7fccb6d402063c80612344a0a50f86a25
echo("<script>\n");
echo("document.getElementById(\"button_odeslat\").click();");
echo("</script>");
}
?>
<script>
    function submitform()
    {
        document.myform.submit();
    }
</script>
</html>
