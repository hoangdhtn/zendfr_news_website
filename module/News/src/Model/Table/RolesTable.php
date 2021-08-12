<?php 

namespace News\Model\Table;

use Laminas\Crypt\Password\Bcrypt;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Laminas\Filter;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\I18n;
use Laminas\InputFilter;
use Laminas\Paginator\Adapter\DbSelect;
use Laminas\Paginator\Paginator;
use Laminas\Validator;


//use News\Model\Entity\TintucEntity;

class RolesTable extends AbstractTableGateway
{
	protected $adapter;          # adapter to use to connect to the database
	protected $table = 'roles';  # our table. one we want to store data in 

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}



}












?>