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


use News\Model\Entity\TintucEntity;

class TintucTable extends AbstractTableGateway
{
	protected $adapter;          # adapter to use to connect to the database
	protected $table = 'tintuc';  # our table. one we want to store data in 

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}

	public function fetchAllTintuc($paginated = false)
    {
    	 $sqlQuery = $this->sql->select()
            ->join('theloai', 'theloai.id='.$this->table.'.idTheLoai', ['TheLoai' => 'TenTheLoai'], 'left');
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		if($paginated) {
			$classMethod = new ClassMethodsHydrator();
			$entity      = new TintucEntity();
			$resultSet   = new HydratingResultSet($classMethod, $entity);

			$paginatorAdapter = new DbSelect(
				$sqlQuery,
				$this->adapter,
				$resultSet
			);

			$paginator = new Paginator($paginatorAdapter);

			return $paginator;
		}

    }
    public function fetchNewsByCate($paginated = false, int $id)
    {
    	 $sqlQuery = $this->sql->select()
            ->join('theloai', 'theloai.id='.$this->table.'.idTheLoai', ['TheLoai' => 'TenTheLoai'], 'left')
        	->where(['idTheLoai' => $id]);
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		if($paginated) {
			$classMethod = new ClassMethodsHydrator();
			$entity      = new TintucEntity();
			$resultSet   = new HydratingResultSet($classMethod, $entity);

			$paginatorAdapter = new DbSelect(
				$sqlQuery,
				$this->adapter,
				$resultSet
			);

			$paginator = new Paginator($paginatorAdapter);

			return $paginator;
		}
	}


    public function fetchNewsKeyWord(string $kw)
	{
		// $sqlQuery = $this->sql->select()
		// 	->where('TieuDe', 'new');

		// $sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);
		// $handler  = $sqlStmt->execute();

		// if($paginated) {
		// 	$classMethod = new ClassMethodsHydrator();
		// 	$entity      = new TintucEntity();
		// 	$resultSet   = new HydratingResultSet($classMethod, $entity);

		// 	$paginatorAdapter = new DbSelect(
		// 		$sqlQuery,
		// 		$this->adapter,
		// 		$resultSet
		// 	);

		// 	$paginator = new Paginator($paginatorAdapter);

		// 	return $paginator;
		// }

		//return $handler;

		$configArray   = [
        'driver'   => 'Mysqli',
		    'username' => 'root',
		    'password' => '',
		    'database' => 'news',
		    'hostname' => 'localhost',
		    'charset'  => 'utf8', //latin1
		    ];

		    $dbadapter = new \Zend\Db\Adapter\Adapter($configArray);
		    $query = "select * from tintuc where TieuDe Like " . "'%".$kw."%'";

		    $results = $dbadapter->query($query,
		        \Zend\Db\Adapter\Adapter::QUERY_MODE_EXECUTE);

		    $banner = [];
		    
		    foreach ($results as $item) {
		        $banner[] = $item;
		    }

		return $banner;
	}

    public function fetchNewsById(int $newsId)
	{
		$sqlQuery = $this->sql->select()
		    ->join('theloai', 'theloai.id='.$this->table.'.idTheLoai', ['TheLoai' => 'TenTheLoai'], 'left')
			->where([$this->table.'.id' => $newsId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler  = $sqlStmt->execute()->current();

		if(!$handler) {
			return null;
		}

		$classMethod = new ClassMethodsHydrator();
		$entity      = new TintucEntity();
		$classMethod->hydrate($handler, $entity); 

		return $entity;
	}

	public function addNews(array $data , string $img)
	{
		$timeNow = date('Y-m-d H:i:s');
		$values = [
			'TieuDe' => $data['tieude_news'],
			'TomTat'    => $data['tomtat_news'],
			'NoiDung' => $data['noidung_news'],
			'Hinh'	=> $img,
			'idTheLoai' => $data['theloai'],
			'created_at'  => $timeNow,
			'updated_at' => $timeNow,
		];

		$sqlQuery = $this->sql->insert()->values($values);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	public function updateNews(array $data, string $img, int $newsId){

		$timeNow = date('Y-m-d H:i:s');
		$values = [
			'TieuDe' => $data['tieude_news'],
			'TomTat'    => $data['tomtat_news'],
			'NoiDung' => $data['noidung_news'],
			'Hinh'	=> $img,
			'idTheLoai' => $data['theloai'],
			'updated_at' => $timeNow,
		];

		$sqlQuery = $this->sql->update()->set($values)->where(['id' => $newsId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();

	}

	public function deleteNews(int $newsId)
	{        
        #$sqlQuery = $this->sql->update()->set(['active' => 0])->where(['user_id' => $userId]);
		$sqlQuery = $this->sql->delete()->where(['id' => $newsId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}
	public function getSearchnewsFilter(){
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'search',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);
		# csrf 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! Refill the form and try again',
								],
							],
						],
					],
				]
			)
		);

		# be sure to return the input filter
		return $inputFilter;
	}
	public function getEditNewsFilter(){
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'tieude_news',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);
		# filter and validate tomtat 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'tomtat_news',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);
		# filter and validate Noidung
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'noidung_news',
					'required' => true,
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);

		# csrf 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! Refill the form and try again',
								],
							],
						],
					],
				]
			)
		);

		# be sure to return the input filter
		return $inputFilter;
	}
	public function getAddNewsFilter(){
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'tieude_news',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);
		# filter and validate tomtat 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'tomtat_news',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);
		# filter and validate Noidung
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'noidung_news',
					'required' => true,
					'validators' => [
						['name' => Validator\NotEmpty::class],
					],
				]
			)
		);

		# csrf 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! Refill the form and try again',
								],
							],
						],
					],
				]
			)
		);

		# be sure to return the input filter
		return $inputFilter;
	}
}












?>