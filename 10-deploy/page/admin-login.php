<?php
	$password = 'g7e3G6?vnKX>myRY';

	if (!empty($_POST['password']) && $_POST['password'] === $password) {
		$_SESSION['admin'] = true;

		header('Location: /');
	}
?>
<article>
	<form method="post">
		<input type="password" name="password" placeholder="Password" />
		<button type="submit">Accedi</button>
	</form>
</article>