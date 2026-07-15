<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_recipientes extends CI_Migration
{
    public function up()
    {
        $this->db->query("
			CREATE TABLE recipientes (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				codigo VARCHAR(20) NOT NULL,
				descricao VARCHAR(100) NULL,
				status ENUM('estoque','em_uso','manutencao','inativo') NOT NULL DEFAULT 'estoque',
				saida_atual_id INT UNSIGNED NULL,
				ponto_entrega_atual_id INT UNSIGNED NULL,
				localizacao_atual VARCHAR(150) NULL,
				created_at DATETIME NOT NULL,
				updated_at DATETIME NULL,
				PRIMARY KEY (id),
				UNIQUE KEY uq_recipientes_codigo (codigo),
				KEY idx_recipientes_status (status),
				CONSTRAINT fk_recipientes_ponto_entrega FOREIGN KEY (ponto_entrega_atual_id) REFERENCES pontos_entrega (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS recipientes');
    }
}
