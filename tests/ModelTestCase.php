<?php

use PHPUnit\Framework\TestCase;

/**
 * Cada teste roda dentro de uma transacao que e desfeita no tearDown,
 * garantindo isolamento sem precisar de um banco de dados dedicado para
 * testes. trans_strict(FALSE) evita que os trans_begin/commit internos
 * dos proprios Models (ex: Saida_model::registrar_saida) finalizem essa
 * transacao externa antes da hora.
 */
abstract class ModelTestCase extends TestCase {

	/** @var CI_DB_query_builder */
	protected $db;

	protected function setUp(): void
	{
		parent::setUp();

		$CI =& get_instance();
		$this->db = $CI->db;

		$this->db->trans_strict(FALSE);
		$this->db->trans_begin();
	}

	protected function tearDown(): void
	{
		$this->db->trans_rollback();
		parent::tearDown();
	}

	protected function criar_usuario($tipo_usuario, $email, $situacao = 'ativo')
	{
		$this->db->insert('usuarios', array(
			'nome' => ucfirst($tipo_usuario).' Teste',
			'email' => $email,
			'senha_hash' => password_hash('senha123', PASSWORD_DEFAULT),
			'tipo_usuario' => $tipo_usuario,
			'situacao' => $situacao,
			'created_at' => date('Y-m-d H:i:s'),
		));

		return $this->db->insert_id();
	}

	protected function criar_rota_com_ponto()
	{
		$this->db->insert('rotas', array(
			'nome' => 'Rota Teste PHPUnit',
			'ativa' => 1,
			'created_at' => date('Y-m-d H:i:s'),
		));
		$rota_id = $this->db->insert_id();

		$this->db->insert('pontos_entrega', array(
			'rota_id' => $rota_id,
			'nome' => 'Ponto Teste PHPUnit',
			'ordem' => 1,
			'ativo' => 1,
			'created_at' => date('Y-m-d H:i:s'),
		));
		$ponto_id = $this->db->insert_id();

		return array('rota_id' => $rota_id, 'ponto_id' => $ponto_id);
	}

	protected function criar_recipiente($status = 'estoque')
	{
		$sufixo = substr(uniqid(), -6);

		$this->db->insert('recipientes', array(
			'codigo' => 'TST-'.$sufixo,
			'descricao' => 'Recipiente de teste',
			'status' => $status,
			'localizacao_atual' => 'Estoque Central',
			'created_at' => date('Y-m-d H:i:s'),
		));

		return $this->db->insert_id();
	}
}
