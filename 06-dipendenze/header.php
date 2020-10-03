		<header>
			<h1>
				<a href="/">Blog del Corso</a>
			</h1>
			<nav>
				<ul>
					<?php
						$articoli = $pdo->query('select * from articoli');

						foreach ($articoli as $articolo) {
							?>
								<li>
									<a href="/post/<?php echo $articolo['id']; ?>"><?php echo $articolo['titolo']; ?></a>
								</li>
							<?php
						}
					?>
				</ul>
			</nav>
			<?php
				if ($_SESSION['admin'] ?? false) {
					?>
						<p>Benvenuto admin, <a href="/logout">esci</a></p>
					<?php
				}
			?>
		</header>