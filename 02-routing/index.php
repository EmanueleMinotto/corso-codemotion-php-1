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
				foreach (glob("articoli/*.php") as $filename) {
					include($filename);
				}
			?>
		</section>

		<?php include('footer.php'); ?>
	</body>
</html>