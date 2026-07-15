<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Create_entrada_itens extends CI_Migration
{
    public function up()
    {
        $this->db->query('
			CREATE TABLE entrada_itens (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				entrada_id INT UNSIGNED NOT NULL,
				recipiente_id INT UNSIGNED NOT NULL,
				saida_item_id INT UNSIGNED NULL,
				created_at DATETIME NOT NULL,
				PRIMARY KEY (id),
				UNIQUE KEY uq_entrada_itens (entrada_id, recipiente_id),
				KEY idx_entrada_itens_recipiente (recipiente_id),
				CONSTRAINT fk_entrada_itens_entrada FOREIGN KEY (entrada_id) REFERENCES entradas (id),
				CONSTRAINT fk_entrada_itens_recipiente FOREIGN KEY (recipiente_id) REFERENCES recipientes (id),
				CONSTRAINT fk_entrada_itens_saida_item FOREIGN KEY (saida_item_id) REFERENCES saida_itens (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		');

        // Fecha a referencia pendente de saida_itens.entrada_item_id, agora que entrada_itens existe.
        $this->db->query('
			ALTER TABLE saida_itens
			ADD CONSTRAINT fk_saida_itens_entrada_item FOREIGN KEY (entrada_item_id) REFERENCES entrada_itens (id)
		');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE saida_itens DROP FOREIGN KEY fk_saida_itens_entrada_item');
        $this->db->query('DROP TABLE IF EXISTS entrada_itens');
    }
}
