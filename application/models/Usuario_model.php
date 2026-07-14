<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario_model extends CI_Model {

	protected $table = 'usuarios';

	public function find($id)
	{
		return $this->db->where('id', $id)->get($this->table)->row();
	}

	public function find_by_email($email)
	{
		return $this->db->where('email', $email)->get($this->table)->row();
	}

	public function all($tipo_usuario = NULL)
	{
		if ($tipo_usuario !== NULL)
		{
			$this->db->where('tipo_usuario', $tipo_usuario);
		}
		return $this->db->order_by('nome', 'ASC')->get($this->table)->result();
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

	public function set_situacao($id, $situacao)
	{
		return $this->update($id, array('situacao' => $situacao));
	}

	public function email_em_uso($email, $ignorar_id = NULL)
	{
		$this->db->where('email', $email);
		if ($ignorar_id !== NULL)
		{
			$this->db->where('id !=', $ignorar_id);
		}
		return $this->db->get($this->table)->num_rows() > 0;
	}
}
