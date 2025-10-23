<?php

namespace App\BD;

require_once __DIR__."\Configuracao.php";

class MySQL {
	
	private $connection;
	
	public function __construct() {
		$this->connection = new \mysqli(HOST, USUARIO, SENHA, BANCO);

		$this->connection->set_charset("utf8");
	}

	public function search(string $sql, string $types, array $params) : array {
		$stmt = $this->connection->prepare($sql);

		if(!($types == "" || empty($params))) {
			$stmt->bind_param($types, ...$params);
		}

		$stmt->execute();

		$result = $stmt->get_result();
		
    	return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
	}

	public function execute(string $sql, string $types, array $params) : int {
		$stmt = $this->connection->prepare($sql);

		if(!($types == "" || empty($params))) {
			$stmt->bind_param($types, ...$params);
		}

		$stmt->execute();

		return $this->connection->insert_id;
	}
}

?>