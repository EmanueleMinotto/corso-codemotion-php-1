<?php
	if (!empty($_POST['commento'])) {
		$conn->insert('commenti', array(
			'commento' => $_POST['commento'],
			'creato' => time(),
			'path' => $_SERVER['REQUEST_URI'],
		));
	}

	$rows = $conn->fetchAllAssociative('select * from commenti where path = ?', [
		$_SERVER['REQUEST_URI'],
	]);
?>
<article>
	<hr />
	<h3>Commenti</h3>

	<dl>
		<?php
			foreach ($rows as $row) {
				?>
					<dt><?php echo date('Y-m-d H:i:s', $row['creato']); ?></dt>
					<dd><?php echo $row['commento']; ?></dd>
				<?php
			}
		?>
	</dl>

	<form method="post">
		<textarea name="commento" placeholder="Commenta l'articolo"></textarea>
		<button type="submit">Invia</button>
	</form>
</article>