<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rota_model extends MY_Model
{
    protected $table = 'rotas';

    public function find($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function all($apenas_ativas = false)
    {
        if ($apenas_ativas) {
            $this->db->where('ativa', 1);
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
}
