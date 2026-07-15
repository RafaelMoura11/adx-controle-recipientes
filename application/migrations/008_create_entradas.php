<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_entradas extends CI_Migration
{
    public function up()
    {
        $this->db->query("
			CREATE TABLE entradas (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				usuario_registrou_id INT UNSIGNED NOT NULL,
				motorista_id INT UNSIGNED NULL,
				data_hora_entrada DATETIME NOT NULL,
				observacoes TEXT NULL,
				origem ENUM('painel','api') NOT NULL DEFAULT 'painel',
				created_at DATETIME NOT NULL,
				updated_at DATETIME NULL,
				PRIMARY KEY (id),
				KEY idx_entradas_data (data_hora_entrada),
				CONSTRAINT fk_entradas_usuario_registrou FOREIGN KEY (usuario_registrou_id) REFERENCES usuarios (id),
				CONSTRAINT fk_entradas_motorista FOREIGN KEY (motorista_id) REFERENCES usuarios (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS entradas');
    }
}
