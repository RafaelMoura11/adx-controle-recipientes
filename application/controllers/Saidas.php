<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Saida_model $Saida_model
 * @property Rota_model $Rota_model
 * @property PontoEntrega_model $PontoEntrega_model
 * @property Usuario_model $Usuario_model
 */
class Saidas extends Operador_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Saida_model');
        $this->load->model('Rota_model');
        $this->load->model('PontoEntrega_model');
        $this->load->model('Usuario_model');
    }

    public const POR_PAGINA = 10;

    public function index()
    {
        $pagina_atual = max(1, (int) $this->input->get('pagina', true));
        $total = $this->Saida_model->contar();

        $this->pagination->initialize(montar_config_paginacao(current_url(), $total, self::POR_PAGINA));

        render_page('saidas/index', array(
            'saidas' => $this->Saida_model->all(self::POR_PAGINA, ($pagina_atual - 1) * self::POR_PAGINA),
            'links_paginacao' => $this->pagination->create_links(),
        ));
    }

    public function novo()
    {
        render_page('saidas/form', $this->_dados_formulario());
    }

    public function criar()
    {
        $this->form_validation->set_rules('motorista_id', 'Motorista', 'required|integer');
        $this->form_validation->set_rules('data_hora_saida', 'Data e hora da saida', 'required');

        if ($this->form_validation->run() === false) {
            render_page('saidas/form', $this->_dados_formulario(null, validation_errors()));
            return;
        }

        $rota_id = $this->input->post('rota_id', true) ?: null;
        $motorista_id = (int) $this->input->post('motorista_id', true);
        $data_hora_saida = str_replace('T', ' ', $this->input->post('data_hora_saida', true)).':00';
        $observacoes = $this->input->post('observacoes', true);

        $pontos_post = $this->input->post('pontos');
        $pontos = array();

        if (is_array($pontos_post)) {
            foreach ($pontos_post as $ponto_entrega_id => $codigos_texto) {
                $codigos = parse_codigos_recipientes($codigos_texto);

                if (! empty($codigos)) {
                    $pontos[] = array(
                        'ponto_entrega_id' => (int) $ponto_entrega_id,
                        'codigos' => $codigos,
                    );
                }
            }
        }

        $resultado = $this->Saida_model->registrar_saida(
            $motorista_id,
            $rota_id,
            $data_hora_saida,
            $observacoes,
            $pontos,
            $this->usuario_logado->id,
            'painel'
        );

        if (! $resultado['sucesso']) {
            render_page('saidas/form', $this->_dados_formulario(null, $resultado['erro']));
            return;
        }

        $this->session->set_flashdata('sucesso', 'Saida registrada com sucesso.');
        redirect('saidas/detalhe/'.$resultado['saida_id']);
    }

    public function detalhe($id)
    {
        $saida = $this->Saida_model->find($id);

        if (! $saida) {
            show_404();
        }

        render_page('saidas/detalhe', array(
            'saida' => $saida,
            'itens' => $this->Saida_model->itens_por_ponto($id),
        ));
    }

    private function _dados_formulario($saida = null, $erro = null)
    {
        $rotas = $this->Rota_model->all(true);
        $rotas_com_pontos = array();

        foreach ($rotas as $rota) {
            $rotas_com_pontos[] = array(
                'id' => $rota->id,
                'nome' => $rota->nome,
                'pontos' => $this->PontoEntrega_model->por_rota($rota->id, true),
            );
        }

        return array(
            'motoristas' => $this->Usuario_model->all('motorista'),
            'rotas' => $rotas_com_pontos,
            'erro' => $erro,
        );
    }
}
