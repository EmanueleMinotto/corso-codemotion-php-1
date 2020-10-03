<?php
	$articoli = json_decode(file_get_contents('comments.json'), true) ?: [];
	$commenti = $articoli[$_SERVER['REQUEST_URI']] ?? [];

	if (!empty($_POST['commento'])) {
		$commenti[time()] = $_POST['commento'];

		$articoli[$_SERVER['REQUEST_URI']] = $commenti;

		file_put_contents('comments.json', json_encode($articoli));
	}
?>
<article>
	<hr />
	<h3>Commenti</h3>

	<dl>
		<?php
			foreach ($commenti as $timestamp => $commento) {
				?>
					<dt><?php echo date('Y-m-d H:i:s', $timestamp); ?></dt>
					<dd><?php echo $commento; ?></dd>
				<?php
			}
		?>
	</dl>

	<form method="post">
		<textarea name="commento" placeholder="Commenta l'articolo"></textarea>
		<button type="submit">Invia</button>
	</form>
</article>