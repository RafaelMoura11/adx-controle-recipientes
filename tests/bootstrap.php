<?php
/**
 * Bootstrap minimo para rodar os Models do CI3 fora do contexto HTTP,
 * sem depender do autoload.php da aplicacao (session/form_validation/etc),
 * que nao fazem sentido em testes de unidade dos Models.
 */

define('ENVIRONMENT', 'testing');
define('BASEPATH', realpath(__DIR__.'/../system').DIRECTORY_SEPARATOR);
define('APPPATH', realpath(__DIR__.'/../application').DIRECTORY_SEPARATOR);

require_once __DIR__.'/../vendor/autoload.php';
require_once BASEPATH.'core/Common.php';
require_once BASEPATH.'core/Model.php';
require_once BASEPATH.'database/DB.php';

/**
 * Loader minimo: carrega e memoiza Models na instancia global de teste,
 * o suficiente para o padrao "$this->load->model('Xxx_model')" usado
 * dentro dos proprios Models (ex: Saida_model carregando Recipiente_model).
 */
class Test_CI_Loader {

	public function model($model, $name = '')
	{
		$CI =& get_instance();
		$name = $name ?: $model;

		if (isset($CI->$name))
		{
			return $this;
		}

		require_once APPPATH.'models/'.$model.'.php';
		$CI->$name = new $model();

		return $this;
	}
}

class Test_CI_Singleton {
	public $db;
	public $load;
}

$GLOBALS['_ci_test_instance'] = new Test_CI_Singleton();
$GLOBALS['_ci_test_instance']->load = new Test_CI_Loader();
$GLOBALS['_ci_test_instance']->db = DB();

function &get_instance()
{
	return $GLOBALS['_ci_test_instance'];
}
