<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PontoEntrega_model extends CI_Model {

	protected $table = 'pontos_entrega';

	public function find($id)
	{
		return $this->db->where('id', $id)->get($this->table)->row();
	}

	public function por_rota($rota_id, $apenas_ativos = FALSE)
	{
		$this->db->where('rota_id', $rota_id);
		if ($apenas_ativos)
		{
			$this->db->where('ativo', 1);
		}
		return $this->db->order_by('ordem', 'ASC')->get($this->table)->result();
	}

	public function create(array $dados)
	{
		$dados['created_at'] = date('Y-m-d H:i:s');
		$this->db->insert($this->table, $dados);
		return $this->db->insert_id();
	}

	public function update($id, array $dados)
	{
		$dados['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->where('id', $id)->update($this->table, $dados);
	}

	public function set_ativo($id, $ativo)
	{
		return $this->update($id, array('ativo' => $ativo ? 1 : 0));
	}
}
