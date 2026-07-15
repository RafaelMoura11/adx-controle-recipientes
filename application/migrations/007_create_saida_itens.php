<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_saida_itens extends CI_Migration
{
    public function up()
    {
        $this->db->query("
			CREATE TABLE saida_itens (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				saida_id INT UNSIGNED NOT NULL,
				ponto_entrega_id INT UNSIGNED NOT NULL,
				recipiente_id INT UNSIGNED NOT NULL,
				status_item ENUM('em_uso','retornado') NOT NULL DEFAULT 'em_uso',
				entrada_item_id INT UNSIGNED NULL,
				created_at DATETIME NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY uq_saida_itens (saida_id, recipiente_id),
				KEY idx_saida_itens_recipiente (recipiente_id),
				KEY idx_saida_itens_ponto (ponto_entrega_id),
				CONSTRAINT fk_saida_itens_saida FOREIGN KEY (saida_id) REFERENCES saidas (id),
				CONSTRAINT fk_saida_itens_ponto FOREIGN KEY (ponto_entrega_id) REFERENCES pontos_entrega (id),
				CONSTRAINT fk_saida_itens_recipiente FOREIGN KEY (recipiente_id) REFERENCES recipientes (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS saida_itens');
    }
}
