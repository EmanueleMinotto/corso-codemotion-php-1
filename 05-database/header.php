		<header>
			<h1>
				<a href="/">Blog del Corso</a>
			</h1>
			<nav>
				<ul>
					<li>
						<a href="/post/articolo1">Articolo 1</a>
					</li>
					<li>
						<a href="/post/articolo2">Articolo 2</a>
					</li>
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