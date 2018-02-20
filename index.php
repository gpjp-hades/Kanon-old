<!doctype html>
<html>
	<head>
		<meta charset="utf8" />
		<title>Zadání maturitního kánonu.</title>
	</head>

	<body>
	<?php
		if($_POST["button"]=="Zkontrolovat seznam"){
			$action = "preview.php";
		}
		else {
			$action = "index.php";
		}
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
