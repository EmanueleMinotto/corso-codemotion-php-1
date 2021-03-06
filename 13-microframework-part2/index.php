<?php include(__DIR__.'/configuration.php'); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Blog del Corso</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="https://unpkg.com/awsm.css/dist/awsm.min.css" />

		<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
		<link rel="stylesheet" type="text/css" href="/css/rating.css">
		<script type="text/javascript" src="/js/rating.js"></script>
	</head>
	<body>
		<?php include(__DIR__.'/header.php'); ?>

		<section>
			<?php
				if ('/' === $_SERVER['REQUEST_URI']) {
					$articoli = $conn->fetchAllAssociative('select * from articoli');

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

					$articolo = $conn->fetchAssociative('select * from articoli where id = ?', array(
						$matches[1] ?? 0,
					));

					if (!empty($articolo)) {
						?>
							<article>
								<header>
									<h2><?php echo $articolo['titolo']; ?></h2>
									<p>Scritto il <?php echo date('Y-m-d', $articolo['creato']); ?></p>
								</header>
								<?php echo $articolo['contenuto']; ?>
							</article>
						<?php

						include(__DIR__.'/comments-form.php');
					} else {
						preg_match('/^\/([a-z0-9\-]+)/i', $_SERVER['REQUEST_URI'], $matches);

						if (!empty($matches[1]) && file_exists(sprintf(__DIR__.'/page/%s.php', $matches[1]))) {
							include(sprintf(__DIR__.'/page/%s.php', $matches[1]));
						} else {
							include(__DIR__.'/404.php');
						}
					}
				}
			?>
		</section>

		<?php include(__DIR__.'/footer.php'); ?>
	</body>
</html>