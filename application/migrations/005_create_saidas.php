<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_saidas extends CI_Migration
{
    public function up()
    {
        $this->db->query("
			CREATE TABLE saidas (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				motorista_id INT UNSIGNED NOT NULL,
				rota_id INT UNSIGNED NULL,
				usuario_registrou_id INT UNSIGNED NOT NULL,
				data_hora_saida DATETIME NOT NULL,
				observacoes TEXT NULL,
				status ENUM('aberta','parcialmente_retornada','concluida') NOT NULL DEFAULT 'aberta',
				origem ENUM('painel','api') NOT NULL DEFAULT 'painel',
				created_at DATETIME NOT NULL,
				updated_at DATETIME NULL,
				PRIMARY KEY (id),
				KEY idx_saidas_data (data_hora_saida),
				KEY idx_saidas_motorista (motorista_id),
				CONSTRAINT fk_saidas_motorista FOREIGN KEY (motorista_id) REFERENCES usuarios (id),
				CONSTRAINT fk_saidas_rota FOREIGN KEY (rota_id) REFERENCES rotas (id),
				CONSTRAINT fk_saidas_usuario_registrou FOREIGN KEY (usuario_registrou_id) REFERENCES usuarios (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");

        // Agora que 'saidas' existe, fecha a referencia pendente de recipientes.saida_atual_id
        $this->db->query('
			ALTER TABLE recipientes
			ADD CONSTRAINT fk_recipientes_saida_atual FOREIGN KEY (saida_atual_id) REFERENCES saidas (id)
		');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE recipientes DROP FOREIGN KEY fk_recipientes_saida_atual');
        $this->db->query('DROP TABLE IF EXISTS saidas');
    }
}
