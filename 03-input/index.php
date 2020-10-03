<!DOCTYPE html>
<html>
	<head>
		<title>Blog del Corso</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="https://unpkg.com/awsm.css/dist/awsm.min.css" />
	</head>
	<body>
		<?php include('header.php'); ?>

		<section>
			<?php
				if ('/' === $_SERVER['REQUEST_URI']) {
					foreach (glob("articoli/*.php") as $filename) {
						include($filename);
					}
				} else {
					preg_match('/^\/post\/([a-z0-9]+)/i', $_SERVER['REQUEST_URI'], $matches);

					if (!empty($matches[1]) && file_exists(sprintf('articoli/%s.php', $matches[1]))) {
						include(sprintf('articoli/%s.php', $matches[1]));
					} else {
						include('404.php');
					}
				}
			?>
		</section>

		<?php include('footer.php'); ?>
	</body>
</html>