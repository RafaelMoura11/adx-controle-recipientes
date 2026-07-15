<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Usuario_model $Usuario_model
 */
class Usuarios extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
    }

    public const POR_PAGINA = 10;

    public function index()
    {
        $pagina_atual = max(1, (int) $this->input->get('pagina', true));
        $total = $this->Usuario_model->contar();

        $this->pagination->initialize(montar_config_paginacao(current_url(), $total, self::POR_PAGINA));

        render_page('usuarios/index', array(
            'usuarios' => $this->Usuario_model->all(null, self::POR_PAGINA, ($pagina_atual - 1) * self::POR_PAGINA),
            'links_paginacao' => $this->pagination->create_links(),
        ));
    }

    public function novo()
    {
        render_page('usuarios/form', array(
            'usuario' => null,
            'erro' => null,
        ));
    }

    public function criar()
    {
        $this->form_validation->set_rules('nome', 'Nome', 'required|max_length[150]');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');
        $this->form_validation->set_rules('tipo_usuario', 'Tipo de usuario', 'required|in_list[administrador,operador,motorista]');

        if ($this->form_validation->run() === false) {
            render_page('usuarios/form', array('usuario' => null, 'erro' => null));
            return;
        }

        $email = $this->input->post('email', true);

        if ($this->Usuario_model->email_em_uso($email)) {
            render_page('usuarios/form', array('usuario' => null, 'erro' => 'Já existe um usuário com este e-mail.'));
            return;
        }

        $this->Usuario_model->create(array(
            'nome' => $this->input->post('nome', true),
            'email' => $email,
            'senha_hash' => password_hash($this->input->post('senha', true), PASSWORD_DEFAULT),
            'tipo_usuario' => $this->input->post('tipo_usuario', true),
            'telefone' => $this->input->post('telefone', true) ?: null,
            'cnh' => $this->input->post('cnh', true) ?: null,
            'situacao' => 'ativo',
        ));

        $this->session->set_flashdata('sucesso', 'Usuário cadastrado com sucesso.');
        redirect('usuarios');
    }

    public function editar($id)
    {
        $usuario = $this->Usuario_model->find($id);

        if (! $usuario) {
            show_404();
        }

        render_page('usuarios/form', array('usuario' => $usuario, 'erro' => null));
    }

    public function atualizar($id)
    {
        $usuario = $this->Usuario_model->find($id);

        if (! $usuario) {
            show_404();
        }

        $this->form_validation->set_rules('nome', 'Nome', 'required|max_length[150]');
        $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
        $this->form_validation->set_rules('tipo_usuario', 'Tipo de usuario', 'required|in_list[administrador,operador,motorista]');

        if ($this->form_validation->run() === false) {
            render_page('usuarios/form', array('usuario' => $usuario, 'erro' => null));
            return;
        }

        $email = $this->input->post('email', true);

        if ($this->Usuario_model->email_em_uso($email, $id)) {
            render_page('usuarios/form', array('usuario' => $usuario, 'erro' => 'Já existe um usuário com este e-mail.'));
            return;
        }

        $dados = array(
            'nome' => $this->input->post('nome', true),
            'email' => $email,
            'tipo_usuario' => $this->input->post('tipo_usuario', true),
            'telefone' => $this->input->post('telefone', true) ?: null,
            'cnh' => $this->input->post('cnh', true) ?: null,
        );

        $senha = $this->input->post('senha', true);
        if (! empty($senha)) {
            $dados['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
        }

        $this->Usuario_model->update($id, $dados);

        $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso.');
        redirect('usuarios');
    }

    public function bloquear($id)
    {
        $this->Usuario_model->set_situacao($id, 'bloqueado');
        $this->session->set_flashdata('sucesso', 'Usuário bloqueado.');
        redirect('usuarios');
    }

    public function desbloquear($id)
    {
        $this->Usuario_model->set_situacao($id, 'ativo');
        $this->session->set_flashdata('sucesso', 'Usuário desbloqueado.');
        redirect('usuarios');
    }
}
