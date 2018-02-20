<!doctype html>
<html>
	<head>
		<meta http-equiv="refresh" content="4;url=http://www.gpjp.cz" />
		<meta charset="utf8" />
		<title>Odeslání kánonu</title>
	</head>

	<body>
	<?php
		$jmeno=$_POST["jmeno"];
		$prijmeni=$_POST["prijmeni"];
		$trida=$_POST["trida"];
		$knihy=$_POST["knihy"];	
	 
    $nazev = ia($jmeno) . "-" . ia($prijmeni) . ".gms";
    $nazev = trim($nazev);
    $soubor=fopen($trida . "/" . $nazev, "w+");
    fwrite($soubor, $jmeno . " " . $prijmeni . "\n");
    fwrite($soubor, $knihy);
    fclose($soubor);
    
function ia($text)
    {
         $return = Str_Replace(
                        Array("á","č","ď","é","ě","í","ľ","ň","ó","ř","š","ť","ú","ů","ý","ž","Á","Č","Ď","É","Ě","Í","Ľ","Ň","Ó","Ř","Š","Ť","Ú","Ů","Ý","Ž") ,
                        Array("a","c","d","e","e","i","l","n","o","r","s","t","u","u","y","z","A","C","D","E","E","I","L","N","O","R","S","T","U","U","Y","Z") ,
                        $text);
        $return = Str_Replace(Array(" ", "_"), "-", $return);
        return $return;
    }
    
    ?>

    <p>
	Váš maturitní seznam byl úspěšně odeslán.
	</p>
	</body>
</html>
