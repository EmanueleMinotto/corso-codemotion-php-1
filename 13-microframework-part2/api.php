<?php

include(__DIR__.'/configuration.php');

header('Content-Type: application/json');

switch ($_GET['type']) {
	case 'delete_comment':
		if ($_SESSION['admin'] ?? false) {
			$conn->delete('commenti', array(
				'id' => $_GET['id'],
			));

			echo json_encode([
				'success' => 'Commento eliminato',
			]);
		} else {
			echo json_encode([
				'error' => 'Commento non trovato',
			]);
		}

		break;

	case 'vote_comment':
		$conn->insert('voti_articoli', array(
			'path' => $_GET['path'],
			'voto' => $_GET['voto'],
		));

		echo json_encode([
			'success' => 'Articolo votato',
		]);

		break;

	case 'report_comment':
		mail(
			'admin@example.com',
			'Commento riportato',
			sprintf(
				'è stato riportato il commento: %s',
				$conn->fetchOne('select * from commenti where id = ?', array(
					$_GET['id']
				))
			)
		);

		echo json_encode([
			'success' => 'Commento riportato',
		]);

		break;

	default:
		echo json_encode([
			'error' => 'API not found',
		]);
		break;
}