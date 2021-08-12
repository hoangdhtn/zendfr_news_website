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


use News\Model\Entity\DanhmucfeedEntity;

class DanhmucfeedTable extends AbstractTableGateway
{
	protected $adapter;          # adapter to use to connect to the database
	protected $table = 'danhmucfeed';  # our table. one we want to store data in 

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}

	public function fetchAllDanhMucFeed($paginated = false)
    {
    	 $sqlQuery = $this->sql->select();
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		if($paginated) {
			$classMethod = new ClassMethodsHydrator();
			$entity      = new DanhmucfeedEntity();
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

    public function fetchLinkDanhMucFeed($idDanhMuc)
    {
    	$sqlQuery = $this->sql->select()
            ->where(['idDanhMuc' => $idDanhMuc]);
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute()->current();;

		if(!$handler) {
			return null;
		}

		$classMethod = new ClassMethodsHydrator();
		$entity      = new DanhmucfeedEntity();
		$classMethod->hydrate($handler, $entity);

		return $entity;
    }


	public function addFeed(array $data)
	{
		$values = [
			'TenDanhMuc' => $data['ten'],
			'Link'  => $data['link'],
		];

		$sqlQuery = $this->sql->insert()->values($values);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	public function updateFeed(array $data, int $id){

		$values = [
			'TenDanhMuc' => $data['ten'],
			'Link'  => $data['link'],
		];

		$sqlQuery = $this->sql->update()->set($values)->where(['idDanhMuc' => $id]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();

	}

	public function deleteFeed($id)
	{
		#$sqlQuery = $this->sql->update()->set(['active' => 0])->where(['user_id' => $userId]);
		$sqlQuery = $this->sql->delete()->where(['idDanhMuc' => $id]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

 	//getAddFeedFilter
 	public function getAddFeedFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'ten',
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
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'link',
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

	public function getEditFeedFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate tieude 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'ten',
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
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'link',
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