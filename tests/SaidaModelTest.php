<?php

require_once __DIR__.'/ModelTestCase.php';

final class SaidaModelTest extends ModelTestCase {

	private function saida_model()
	{
		require_once APPPATH.'models/Saida_model.php';
		require_once APPPATH.'models/Recipiente_model.php';
		return new Saida_model();
	}

	public function test_registra_saida_com_sucesso_e_atualiza_estado_do_recipiente()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.saida1@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.saida1@teste.com');
		$rota = $this->criar_rota_com_ponto();
		$recipiente_id = $this->criar_recipiente('estoque');
		$codigo = $this->db->where('id', $recipiente_id)->get('recipientes')->row()->codigo;

		$model = $this->saida_model();

		$resultado = $model->registrar_saida(
			$motorista_id,
			$rota['rota_id'],
			date('Y-m-d H:i:s'),
			'Observacao de teste',
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array($codigo))),
			$operador_id
		);

		$this->assertTrue($resultado['sucesso'], (string) $resultado['erro']);
		$this->assertNotNull($resultado['saida_id']);

		$recipiente = $this->db->where('id', $recipiente_id)->get('recipientes')->row();
		$this->assertSame('em_uso', $recipiente->status);
		$this->assertEquals($resultado['saida_id'], $recipiente->saida_atual_id);
		$this->assertEquals($rota['ponto_id'], $recipiente->ponto_entrega_atual_id);

		$item = $this->db->where('saida_id', $resultado['saida_id'])->where('recipiente_id', $recipiente_id)->get('saida_itens')->row();
		$this->assertNotNull($item);
		$this->assertSame('em_uso', $item->status_item);
	}

	public function test_rejeita_recipiente_que_ja_esta_em_uso()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.saida2@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.saida2@teste.com');
		$rota = $this->criar_rota_com_ponto();
		$recipiente_id = $this->criar_recipiente('em_uso');
		$codigo = $this->db->where('id', $recipiente_id)->get('recipientes')->row()->codigo;

		$model = $this->saida_model();

		$resultado = $model->registrar_saida(
			$motorista_id,
			$rota['rota_id'],
			date('Y-m-d H:i:s'),
			NULL,
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array($codigo))),
			$operador_id
		);

		$this->assertFalse($resultado['sucesso']);
		$this->assertStringContainsString('nao esta em estoque', $resultado['erro']);
	}

	public function test_rejeita_recipiente_inexistente()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.saida3@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.saida3@teste.com');
		$rota = $this->criar_rota_com_ponto();

		$model = $this->saida_model();

		$resultado = $model->registrar_saida(
			$motorista_id,
			$rota['rota_id'],
			date('Y-m-d H:i:s'),
			NULL,
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array('NAO-EXISTE'))),
			$operador_id
		);

		$this->assertFalse($resultado['sucesso']);
		$this->assertStringContainsString('nao encontrado', $resultado['erro']);
	}

	public function test_rejeita_codigos_duplicados_na_mesma_saida()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.saida4@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.saida4@teste.com');
		$rota = $this->criar_rota_com_ponto();
		$recipiente_id = $this->criar_recipiente('estoque');
		$codigo = $this->db->where('id', $recipiente_id)->get('recipientes')->row()->codigo;

		$model = $this->saida_model();

		$resultado = $model->registrar_saida(
			$motorista_id,
			$rota['rota_id'],
			date('Y-m-d H:i:s'),
			NULL,
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array($codigo, $codigo))),
			$operador_id
		);

		$this->assertFalse($resultado['sucesso']);
		$this->assertStringContainsString('duplicados', $resultado['erro']);
	}

	public function test_rejeita_saida_sem_nenhum_recipiente()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.saida5@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.saida5@teste.com');
		$rota = $this->criar_rota_com_ponto();

		$model = $this->saida_model();

		$resultado = $model->registrar_saida(
			$motorista_id,
			$rota['rota_id'],
			date('Y-m-d H:i:s'),
			NULL,
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array())),
			$operador_id
		);

		$this->assertFalse($resultado['sucesso']);
		$this->assertStringContainsString('ao menos um recipiente', $resultado['erro']);
	}
}
