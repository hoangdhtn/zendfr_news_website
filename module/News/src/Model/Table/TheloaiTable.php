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


use News\Model\Entity\TheloaiEntity;

class TheloaiTable extends AbstractTableGateway
{
	protected $adapter;          # adapter to use to connect to the database
	protected $table = 'theloai';  # our table. one we want to store data in 

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}


	public function fetchAllTheloai() 
    {
    	 $sqlQuery = $this->sql->select();
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		$classMethod = new ClassMethodsHydrator();
		$entity      = new TheloaiEntity();
		$resultSet   = new HydratingResultSet($classMethod, $entity);

		$resultSet->initialize($handler);

		return $resultSet;
		

    }

    public function fetchAllTheloaiPa($paginated = false) 
    {
    	 $sqlQuery = $this->sql->select();
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		if($paginated) {
			$classMethod = new ClassMethodsHydrator();
			$entity      = new TheloaiEntity();
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

    

    public function fetchTheloaiById(int $id)
	{
		$sqlQuery = $this->sql->select()
			->where(['id' => $id]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler  = $sqlStmt->execute()->current();

		if(!$handler) {
			return null;
		}

		$classMethod = new ClassMethodsHydrator();
		$entity      = new TheloaiEntity();
		$classMethod->hydrate($handler, $entity); 

		return $entity;
	}

	public function addCate(array $data)
	{
		$timeNow = date('Y-m-d H:i:s');
		$values = [
			'TenTheLoai' => $data['theloai'],
			'created_at'  => $timeNow,
			'updated_at' => $timeNow,
		];

		$sqlQuery = $this->sql->insert()->values($values);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	public function updateCate(array $data, int $id){

		$timeNow = date('Y-m-d H:i:s');
		$values = [
			'TenTheLoai' => $data['theloai'],
			'updated_at' => $timeNow,
		];

		$sqlQuery = $this->sql->update()->set($values)->where(['id' => $id]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();

	}

	public function deleteCate($id)
	{
		#$sqlQuery = $this->sql->update()->set(['active' => 0])->where(['user_id' => $userId]);
		$sqlQuery = $this->sql->delete()->where(['id' => $id]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	public function getEditCateFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'theloai',
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
	public function getAddCateFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'theloai',
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


} 












?>