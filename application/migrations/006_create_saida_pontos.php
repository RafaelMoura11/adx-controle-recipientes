<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_saida_pontos extends CI_Migration {

	public function up()
	{
		$this->db->query("
			CREATE TABLE saida_pontos (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				saida_id INT UNSIGNED NOT NULL,
				ponto_entrega_id INT UNSIGNED NOT NULL,
				quantidade_planejada INT UNSIGNED NULL,
				PRIMARY KEY (id),
				UNIQUE KEY uq_saida_pontos (saida_id, ponto_entrega_id),
				CONSTRAINT fk_saida_pontos_saida FOREIGN KEY (saida_id) REFERENCES saidas (id),
				CONSTRAINT fk_saida_pontos_ponto FOREIGN KEY (ponto_entrega_id) REFERENCES pontos_entrega (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
	}

	public function down()
	{
		$this->db->query('DROP TABLE IF EXISTS saida_pontos');
	}
}
