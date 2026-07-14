<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_usuarios extends CI_Migration {

	public function up()
	{
		$this->db->query("
			CREATE TABLE usuarios (
				id INT UNSIGNED NOT NULL AUTO_INCREMENT,
				nome VARCHAR(150) NOT NULL,
				email VARCHAR(150) NOT NULL,
				senha_hash VARCHAR(255) NOT NULL,
				tipo_usuario ENUM('administrador','operador','motorista') NOT NULL,
				situacao ENUM('ativo','bloqueado') NOT NULL DEFAULT 'ativo',
				telefone VARCHAR(20) NULL,
				cnh VARCHAR(20) NULL,
				created_at DATETIME NOT NULL,
				updated_at DATETIME NULL,
				PRIMARY KEY (id),
				UNIQUE KEY uq_usuarios_email (email),
				KEY idx_usuarios_tipo (tipo_usuario)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
		");
	}

	public function down()
	{
		$this->db->query('DROP TABLE IF EXISTS usuarios');
	}
}
