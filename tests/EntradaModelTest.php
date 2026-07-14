<?php

require_once __DIR__.'/ModelTestCase.php';

final class EntradaModelTest extends ModelTestCase {

	private function saida_model()
	{
		require_once APPPATH.'models/Saida_model.php';
		require_once APPPATH.'models/Recipiente_model.php';
		return new Saida_model();
	}

	private function entrada_model()
	{
		require_once APPPATH.'models/Entrada_model.php';
		require_once APPPATH.'models/Recipiente_model.php';
		return new Entrada_model();
	}

	public function test_registra_entrada_devolve_recipiente_ao_estoque_e_conclui_saida()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.entrada1@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.entrada1@teste.com');
		$rota = $this->criar_rota_com_ponto();
		$recipiente_id = $this->criar_recipiente('estoque');
		$codigo = $this->db->where('id', $recipiente_id)->get('recipientes')->row()->codigo;

		$saida_resultado = $this->saida_model()->registrar_saida(
			$motorista_id, $rota['rota_id'], date('Y-m-d H:i:s'), NULL,
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array($codigo))),
			$operador_id
		);
		$this->assertTrue($saida_resultado['sucesso'], (string) $saida_resultado['erro']);

		$entrada_resultado = $this->entrada_model()->registrar_entrada(
			$motorista_id, date('Y-m-d H:i:s'), 'Devolucao teste', array($codigo), $operador_id
		);

		$this->assertTrue($entrada_resultado['sucesso'], (string) $entrada_resultado['erro']);
		$this->assertNotNull($entrada_resultado['entrada_id']);

		$recipiente = $this->db->where('id', $recipiente_id)->get('recipientes')->row();
		$this->assertSame('estoque', $recipiente->status);
		$this->assertNull($recipiente->saida_atual_id);
		$this->assertSame('Estoque Central', $recipiente->localizacao_atual);

		$saida_item = $this->db->where('saida_id', $saida_resultado['saida_id'])->where('recipiente_id', $recipiente_id)->get('saida_itens')->row();
		$this->assertSame('retornado', $saida_item->status_item);
		$this->assertNotNull($saida_item->entrada_item_id);

		$saida = $this->db->where('id', $saida_resultado['saida_id'])->get('saidas')->row();
		$this->assertSame('concluida', $saida->status);
	}

	public function test_saida_com_dois_itens_fica_parcialmente_retornada_ao_devolver_apenas_um()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.entrada2@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.entrada2@teste.com');
		$rota = $this->criar_rota_com_ponto();
		$recipiente_1 = $this->criar_recipiente('estoque');
		$recipiente_2 = $this->criar_recipiente('estoque');
		$codigo_1 = $this->db->where('id', $recipiente_1)->get('recipientes')->row()->codigo;
		$codigo_2 = $this->db->where('id', $recipiente_2)->get('recipientes')->row()->codigo;

		$saida_resultado = $this->saida_model()->registrar_saida(
			$motorista_id, $rota['rota_id'], date('Y-m-d H:i:s'), NULL,
			array(array('ponto_entrega_id' => $rota['ponto_id'], 'codigos' => array($codigo_1, $codigo_2))),
			$operador_id
		);
		$this->assertTrue($saida_resultado['sucesso'], (string) $saida_resultado['erro']);

		$entrada_resultado = $this->entrada_model()->registrar_entrada(
			$motorista_id, date('Y-m-d H:i:s'), NULL, array($codigo_1), $operador_id
		);
		$this->assertTrue($entrada_resultado['sucesso'], (string) $entrada_resultado['erro']);

		$saida = $this->db->where('id', $saida_resultado['saida_id'])->get('saidas')->row();
		$this->assertSame('parcialmente_retornada', $saida->status);

		$recipiente_2_atual = $this->db->where('id', $recipiente_2)->get('recipientes')->row();
		$this->assertSame('em_uso', $recipiente_2_atual->status);
	}

	public function test_rejeita_devolucao_de_recipiente_que_nao_esta_em_uso()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.entrada3@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.entrada3@teste.com');
		$recipiente_id = $this->criar_recipiente('estoque');
		$codigo = $this->db->where('id', $recipiente_id)->get('recipientes')->row()->codigo;

		$resultado = $this->entrada_model()->registrar_entrada(
			$motorista_id, date('Y-m-d H:i:s'), NULL, array($codigo), $operador_id
		);

		$this->assertFalse($resultado['sucesso']);
		$this->assertStringContainsString('nao esta em uso', $resultado['erro']);
	}

	public function test_rejeita_recipiente_inexistente()
	{
		$motorista_id = $this->criar_usuario('motorista', 'motorista.entrada4@teste.com');
		$operador_id = $this->criar_usuario('operador', 'operador.entrada4@teste.com');

		$resultado = $this->entrada_model()->registrar_entrada(
			$motorista_id, date('Y-m-d H:i:s'), NULL, array('NAO-EXISTE'), $operador_id
		);

		$this->assertFalse($resultado['sucesso']);
		$this->assertStringContainsString('nao encontrado', $resultado['erro']);
	}
}
