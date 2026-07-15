<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Seed_dados_iniciais extends CI_Migration
{
    public function up()
    {
        $now = date('Y-m-d H:i:s');

        // --- Usuarios ---
        $usuarios = array(
            array('nome' => 'Administrador', 'email' => 'admin@adx.com', 'senha' => 'admin123', 'tipo_usuario' => 'administrador'),
            array('nome' => 'Operador Um', 'email' => 'operador1@adx.com', 'senha' => 'operador123', 'tipo_usuario' => 'operador'),
            array('nome' => 'Operador Dois', 'email' => 'operador2@adx.com', 'senha' => 'operador123', 'tipo_usuario' => 'operador'),
            array('nome' => 'Motorista Um', 'email' => 'motorista1@adx.com', 'senha' => 'motorista123', 'tipo_usuario' => 'motorista'),
            array('nome' => 'Motorista Dois', 'email' => 'motorista2@adx.com', 'senha' => 'motorista123', 'tipo_usuario' => 'motorista'),
            array('nome' => 'Motorista Tres', 'email' => 'motorista3@adx.com', 'senha' => 'motorista123', 'tipo_usuario' => 'motorista'),
        );

        foreach ($usuarios as $u) {
            $this->db->insert('usuarios', array(
                'nome' => $u['nome'],
                'email' => $u['email'],
                'senha_hash' => password_hash($u['senha'], PASSWORD_DEFAULT),
                'tipo_usuario' => $u['tipo_usuario'],
                'situacao' => 'ativo',
                'created_at' => $now,
            ));
        }

        // --- Rotas + Pontos de entrega ---
        $rotas = array(
            array(
                'nome' => 'Rota Centro',
                'descricao' => 'Entregas na regiao central',
                'pontos' => array('Restaurante Sabor Caseiro', 'Hospital Municipal', 'Escola Estadual Jardim'),
            ),
            array(
                'nome' => 'Rota Zona Sul',
                'descricao' => 'Entregas na zona sul da cidade',
                'pontos' => array('Creche Municipal Girassol', 'Asilo Vida Plena'),
            ),
        );

        foreach ($rotas as $rota) {
            $this->db->insert('rotas', array(
                'nome' => $rota['nome'],
                'descricao' => $rota['descricao'],
                'ativa' => 1,
                'created_at' => $now,
            ));
            $rota_id = $this->db->insert_id();

            $ordem = 1;
            foreach ($rota['pontos'] as $nome_ponto) {
                $this->db->insert('pontos_entrega', array(
                    'rota_id' => $rota_id,
                    'nome' => $nome_ponto,
                    'ordem' => $ordem++,
                    'ativo' => 1,
                    'created_at' => $now,
                ));
            }
        }

        // --- Recipientes (20 em estoque) ---
        for ($i = 1; $i <= 20; $i++) {
            $this->db->insert('recipientes', array(
                'codigo' => 'REC-'.str_pad($i, 6, '0', STR_PAD_LEFT),
                'descricao' => 'Recipiente termico padrao',
                'status' => 'estoque',
                'localizacao_atual' => 'Estoque Central',
                'created_at' => $now,
            ));
        }
    }

    public function down()
    {
        $this->db->where('codigo LIKE', 'REC-%')->delete('recipientes');
        $this->db->empty_table('pontos_entrega');
        $this->db->empty_table('rotas');
        $this->db->where_in('email', array(
            'admin@adx.com', 'operador1@adx.com', 'operador2@adx.com',
            'motorista1@adx.com', 'motorista2@adx.com', 'motorista3@adx.com',
        ))->delete('usuarios');
    }
}
