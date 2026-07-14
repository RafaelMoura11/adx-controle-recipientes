<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_rotas extends CI_Migration {

	public function up()
	{
		$this->db->query("
			CREATE TABLE rotas (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				nome VARCHAR(150) NOT NULL,
				descricao VARCHAR(255) NULL,
				ativa TINYINT(1) NOT NULL DEFAULT 1,
				created_at DATETIME NOT NULL,
				updated_at DATETIME NULL,
				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
	}

	public function down()
	{
		$this->db->query('DROP TABLE IF EXISTS rotas');
	}
}
