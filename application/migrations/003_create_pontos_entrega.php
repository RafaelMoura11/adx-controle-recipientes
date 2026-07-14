<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_pontos_entrega extends CI_Migration {

	public function up()
	{
		$this->db->query("
			CREATE TABLE pontos_entrega (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				rota_id INT UNSIGNED NOT NULL,
				nome VARCHAR(150) NOT NULL,
				endereco VARCHAR(255) NULL,
				ordem INT UNSIGNED NOT NULL DEFAULT 0,
				ativo TINYINT(1) NOT NULL DEFAULT 1,
				created_at DATETIME NOT NULL,
				updated_at DATETIME NULL,
				PRIMARY KEY (id),
				KEY idx_pontos_entrega_rota (rota_id),
				CONSTRAINT fk_pontos_entrega_rota FOREIGN KEY (rota_id) REFERENCES rotas (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
	}

	public function down()
	{
		$this->db->query('DROP TABLE IF EXISTS pontos_entrega');
	}
}
