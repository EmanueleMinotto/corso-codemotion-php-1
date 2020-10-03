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
	<div id="rating">
		<?php
			$voti = $conn->fetchAllAssociative('select voto from voti_articoli where path = ?', [
				$_SERVER['REQUEST_URI'],
			]);
			$voti = array_column($voti, 'voto');

			$voto = round(array_sum($voti) / max(count($voti), 1), 0);

			for ($i = 1; $i <= 5; $i++) { 
				echo sprintf(
					'<input type="radio" class="rating" value="%d" %s />',
					$i,
					$i == $voto ? 'checked' : ''
				);
			}
		?>
	</div>

	<hr />
	<h3>Commenti</h3>

	<dl>
		<?php
			foreach ($rows as $row) {
				?>
					<dt class="commento-<?php echo $row['id']; ?>"><?php echo date('Y-m-d H:i:s', $row['creato']); ?></dt>
					<dd class="commento-<?php echo $row['id']; ?>">
						<?php echo $row['commento']; ?>
						<?php if ($_SESSION['admin'] ?? false) { ?>
							<br />
							<button type="button" id="delete-comment" data-id-commento="<?php echo $row['id']; ?>">Elimina commento</button>
						<?php } ?>
					</dd>
				<?php
			}
		?>
	</dl>

	<form method="post">
		<textarea name="commento" placeholder="Commenta l'articolo"></textarea>
		<button type="submit">Invia</button>
	</form>
</article>

<script type="text/javascript">
    $(function(){
	    $('#rating').rating(function (vote, event) {
	        $.ajax({
	            url: "/api.php",
	            type: "GET",
	            data: {
	            	voto: vote,
	            	type: "vote_comment",
	            	path: "<?php echo $_SERVER['REQUEST_URI']; ?>",
	            },
	        });
	    });

	    $('#delete-comment').click(function () {
	    	$('.commento-' + $(this).attr('data-id-commento')).hide();
	        $.ajax({
	            url: "/api.php",
	            type: "GET",
	            data: {
	            	type: "delete_comment",
	            	id: $(this).attr('data-id-commento'),
	            },
	        });
	    });
    });
</script>