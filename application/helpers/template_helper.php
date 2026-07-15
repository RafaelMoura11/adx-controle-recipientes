<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (! function_exists('render_page')) {
    /**
     * Renderiza uma view de conteudo dentro do layout principal (navbar + Bootstrap).
     */
    function render_page($view, array $data = array())
    {
        $ci = & get_instance();

        $data['conteudo_html'] = $ci->load->view($view, $data, true);

        $ci->load->view('layouts/main', $data);
    }
}
