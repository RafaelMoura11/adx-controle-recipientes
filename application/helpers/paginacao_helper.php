<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('montar_config_paginacao'))
{
	/**
	 * Config padrao (Bootstrap 5) para o CI3 Pagination, usada em todas as
	 * listagens do painel. Pagina via querystring (?pagina=N) e preserva
	 * outros filtros ja presentes na URL (ex: ?status=estoque).
	 */
	function montar_config_paginacao($base_url, $total_rows, $per_page = 20)
	{
		return array(
			'base_url' => $base_url,
			'total_rows' => $total_rows,
			'per_page' => $per_page,
			'page_query_string' => TRUE,
			'query_string_segment' => 'pagina',
			'reuse_query_string' => TRUE,
			'use_page_numbers' => TRUE,
			'num_links' => 2,
			'full_tag_open' => '<nav><ul class="pagination justify-content-center mb-0">',
			'full_tag_close' => '</ul></nav>',
			'first_link' => '&laquo;&laquo;',
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',
			'last_link' => '&raquo;&raquo;',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',
			'next_link' => '&raquo;',
			'next_tag_open' => '<li class="page-item">',
			'next_tag_close' => '</li>',
			'prev_link' => '&laquo;',
			'prev_tag_open' => '<li class="page-item">',
			'prev_tag_close' => '</li>',
			'cur_tag_open' => '<li class="page-item active"><span class="page-link">',
			'cur_tag_close' => '</span></li>',
			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'attributes' => array('class' => 'page-link'),
		);
	}
}
