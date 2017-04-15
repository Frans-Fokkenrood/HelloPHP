<?php
	require_once 'header.php';
	require_once 'tabbalk.php';
	
	if (isset($_POST['vandaag'])) {
		$moment = $_POST['vandaag'];
	} else {
		$moment = isset($_POST['moment']) ? $_POST['moment'] : date('Y-m-d');
	}	// end if
	
	echo '<h1>Hello World DEMO applicatie</h1>' . "\n";
	echo '<form id=phpForm action="" method="post">' . "\n";

	if ($moment == '') {
		echo '<h1><input type="text" name="vandaag"/>' . "\n";
		echo '<input type="submit" value="Zet datum"/></h1>' . "\n";
	}	// end if

	echo '<input type="hidden" name="moment" value="' . $moment . '"/>' . "\n";
	echo '</form>' . "\n";

	if ($moment != '') {
		echo '<h2 onclick="phpForm.moment.value=\'\';phpForm.submit();">Vandaag is ' . $moment . '</h2>' . "\n";
	}	// end if
	
 	$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
 	socket_connect($socket, "localhost", 8888) or die("Could not connect to socket\n");

 	//	Write Request:
 	$output = "Hello PHP and Java worlds, \nthis is a message from XAMPP\n" . "\n";
 	socket_write($socket, $output) or die("Could not write output\n");
	
 	//	Read Response:
 	do {
 		$line = socket_read($socket, 512, PHP_NORMAL_READ) or die("Could not read input\n");
 		echo $line;
 	} while ($line != "\n");
	
 	socket_close($socket);

	require_once 'footer.php';
?>