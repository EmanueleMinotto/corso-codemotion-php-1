<?php
	session_start();

	$pdo = new PDO('sqlite:blog.db');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Blog del Corso</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://unpkg.com/awsm.css/dist/awsm.min.css" />
	</head>
	<body>
		<?php include('header.php'); ?>

		<section>
			<?php
				if ('/' === $_SERVER['REQUEST_URI']) {
					$articoli = $pdo->query('select * from articoli');

					foreach ($articoli as $articolo) {
						?>
							<article>
								<header>
									<h2><?php echo $articolo['titolo']; ?></h2>
									<p>Scritto il <?php echo date('Y-m-d', $articolo['creato']); ?></p>
								</header>
								<?php echo $articolo['contenuto']; ?>
							</article>
						<?php
					}
				} else {
					preg_match('/^\/post\/([0-9]+)/i', $_SERVER['REQUEST_URI'], $matches);

					$articoli = $pdo->query('select * from articoli where id = '.$matches[1]);

					if (!empty($articoli) && $articolo = $articoli->fetch()) {
						?>
							<article>
								<header>
									<h2><?php echo $articolo['titolo']; ?></h2>
									<p>Scritto il <?php echo date('Y-m-d', $articolo['creato']); ?></p>
								</header>
								<?php echo $articolo['contenuto']; ?>
							</article>
						<?php

						include('comments-form.php');
					} else {
						preg_match('/^\/([a-z0-9\-]+)/i', $_SERVER['REQUEST_URI'], $matches);

						if (!empty($matches[1]) && file_exists(sprintf('page/%s.php', $matches[1]))) {
							include(sprintf('page/%s.php', $matches[1]));
						} else {
							include('404.php');
						}
					}
				}
			?>
		</section>

		<?php include('footer.php'); ?>
	</body>
</html>