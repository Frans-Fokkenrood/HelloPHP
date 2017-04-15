<?php
	require_once 'config.php';
	$dbconn = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME) or die(mysql_error());
	require_once 'header.php';
	require_once 'tabbalk.php';
	$moment = isset($_POST['moment']) ? $_POST['moment'] : date('Y-m-d');
?>
<h1>Automaat</h1>

<form id=phpForm action="" method="post">
<table>
<colgroup><col width="50%"/><col width="50%"/></colgroup>
<tr><td class="label">Automaat identificatie (taak):</td><td><input name="taak" value="<?php echo isset($_POST['haalop']) ? $_POST['taak'] : ''; ?>"/></td></tr>
<?php 
	if (isset($_POST['taak'])) {
		$result = mysqli_query($dbconn, "SELECT id, content FROM automaat WHERE name = '" . $_POST['taak'] . "'");
		$row = mysqli_fetch_array($result);
		if ($row) {
			$uuid = $row[0];
			$json = json_decode($row[1], true);
		} else {
			$uuid = '';
			echo '<tr><td></td><td class="fout">Taak ' . $_POST['taak'] . ' onbekend!</td></tr>' . "\n";
			unset($_POST['haalop']);
		}	// end if
		mysqli_free_result($result);
	}	// end if

	if (isset($_POST['haalop'])) {
		echo '<tr><td colspan="2"><hr></td></tr>';
		echo '<tr><td class="label">Deel van de dag:</td><td><input name="dagdeel" value="' . $json['automaat']['dagdeel'] . '"/></td></tr>' . "\n";
		echo '<tr><td class="label">Temperatuur in de woonkamer:</td><td><input name="temperatuur" value="' . $json['automaat']['temperatuur'] . '"/></td></tr>' . "\n";
		echo '<tr><td class="label">Bewoner aanwezig:</td><td><input name="aanwezig" value="' . $json['automaat']['aanwezig'] . '"/></td></tr>' . "\n";
	}	// end if
	
	if (isset($_POST['beslis'])) {
		$json  = 'JSON:{"automaat":{';
		$json .= '"dagdeel":"' . $_POST['dagdeel'] . '",';
		$json .= '"temperatuur":"' . $_POST['temperatuur'] . '",';
		$json .= '"aanwezig":"' . $_POST['aanwezig'] . '"';
		$json .= '}}' . "\n\n";
		
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		socket_connect($socket, "localhost", 8888) or die("Could not connect to socket\n");
		//	Write Request:
		socket_write($socket, $json) or die("Could not write output\n");
		//	Read Response:
		$row = '';
		do {
			$line = socket_read($socket, 512, PHP_NORMAL_READ) or die("Could not read input\n");
			$row .= $line;
		} while ($line != "\n");
		$json = json_decode($row, true);
		echo '<tr><td class="label">Gevuurde regel:</td><td><input name="regel" value="' . $json['automaat']['regel'] . '"/></td></tr>' . "\n";
		echo '<tr><td colspan="2">&nbsp;</td></tr>';
		echo '<tr><td></td><td><img src="lamp-' . $json['automaat']['status'] . '.png"';
		echo ' title="De lamp is ' . $json['automaat']['status'] . '" alt="De lamp is ' . $json['automaat']['status'] . '"/></td></tr>';
		socket_close($socket);
	}	// end if
?>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td></td><td><input type="submit" name="haalop" value="Haal op"/>
<?php
	if (isset($_POST['haalop'])) {
		echo ' &nbsp; <input type="submit" name="beslis" value="Beslis"/>';
	}	// end if
?>
</td></tr>
</table>
<input type="hidden" name="uuid"    value="<?php echo $uuid; ?>"/>
<input type="hidden" name="moment"  value="<?php echo $moment; ?>"/>
</form>

<h2>Vandaag is <?php echo $moment; ?></h2>
<?php
	require_once 'footer.php';
	mysqli_close($dbconn) or die(mysql_error());
?>