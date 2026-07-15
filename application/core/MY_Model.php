<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base para os Models da aplicacao. So existe para documentar (via PHPDoc)
 * as propriedades magicas que o CI_Model resolve em runtime atraves de
 * __get()/get_instance() - sem isso, ferramentas de analise estatica
 * (PHPStan) nao enxergam $this->db/$this->load como validos.
 *
 * @property CI_DB_mysqli_driver $db
 * @property CI_Loader $load
 */
class MY_Model extends CI_Model
{
}
