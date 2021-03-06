<?php 

declare(strict_types=1);

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

use News\Model\Entity\UserEntity;
use News\Model\Entity\TintucEntity;

class UsersTable extends AbstractTableGateway
{
	protected $adapter;          # adapter to use to connect to the database
	protected $table = 'users';  # our table. one we want to store data in

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}

	public function deleteAccount(int $userId)
	{        
        #$sqlQuery = $this->sql->update()->set(['active' => 0])->where(['user_id' => $userId]);
		$sqlQuery = $this->sql->delete()->where(['id' => $userId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}



	public function fetchAllAccounts($paginated = false)
	{
		$sqlQuery = $this->sql->select()->join('roles', 'roles.role_id='.$this->table.'.role_id',
		     ['role'])
		    ->where(['active' => 1])
		    ->order('created_at ASC');
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute();

		if($paginated) {
			$classMethod = new ClassMethodsHydrator();
			$entity      = new UserEntity();
			$resultSet   = new HydratingResultSet($classMethod, $entity);

			$paginatorAdapter = new DbSelect(
				$sqlQuery,
				$this->adapter,
				$resultSet 
			);

			$paginator = new Paginator($paginatorAdapter);

			return $paginator;
		}


		// $sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		// $handler = $sqlStmt->execute();

		// $classMethod = new ClassMethodsHydrator();
		// $entity      = new UserEntity();
		// $resultSet   = new HydratingResultSet($classMethod, $entity);

		// $resultSet->initialize($handler);

		// return $resultSet;
	}

	public function fetchAccountById(int $userId)
	{
		$sqlQuery = $this->sql->select()
		    ->join('roles', 'roles.role_id='.$this->table.'.role_id', ['role_id', 'role'])
			->where(['id' => $userId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler  = $sqlStmt->execute()->current();

		if(!$handler) {
			return null;
		}

		$classMethod = new ClassMethodsHydrator();
		$entity      = new UserEntity();
		$classMethod->hydrate($handler, $entity);

		return $entity;
	}

	public function fetchAccountByEmail(string $email)
	{
		$sqlQuery = $this->sql->select()
            ->join('roles', 'roles.role_id='.$this->table.'.role_id', ['role_id', 'role'])
			->where(['email' => $email]);
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler = $sqlStmt->execute()->current();

		if(!$handler) {
			return null;
		}

		$classMethod = new ClassMethodsHydrator();
		$entity      = new UserEntity();
		$classMethod->hydrate($handler, $entity);

		return $entity;
	}


	public function getForgotFormFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate email 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
						['name' => Filter\StringToLower::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 6,  
								'max' => 128,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'Email address must have at least 6 characters!',
									Validator\StringLength::TOO_LONG => 'Email address must have at most 128 characters!',
								],
							],
						],
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\Db\RecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,
							],
						],
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

	# sanitizes our form data
	public function getCreateFormFilter()
	{

		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate username input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'username',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
						['name' => I18n\Filter\Alnum::class], # allows only [a-zA-Z0-9] characters
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 2,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'T??n ng?????i d??ng ph???i c?? ??t nh???t 2 k?? t???',
									Validator\StringLength::TOO_LONG => 'T??n ng?????i d??ng ph???i c?? t???i ??a 25 k?? t???',
								],
							],
						], 
						[
							'name' => I18n\Validator\Alnum::class,
							'options' => [
								'messages' => [
									I18n\Validator\Alnum::NOT_ALNUM => 'T??n ng?????i d??ng ch??? ???????c bao g???m c??c k?? t??? ch??? v?? s???',
								],
							],
						],
						[
							'name' => Validator\Db\NoRecordExists::class,
							'options' => [
								'table' => $this->table, 
								'field' => 'username',
								'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);
		# filter and validate gender select field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'gender',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						[
							'name' => Validator\InArray::class,
							'options' => [
								'haystack' => ['Female', 'Male', 'Other'],
							],
						],
					],
				]
			)
		);
		# filter and validate email input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],
						['name' => Filter\StringTrim::class], 
						#['name' => Filter\StringToLower::class], comment this line out
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 6,
								'max' => 128,
								'messages' => [
									Validator\StringLength::TOO_SHORT => '?????a ch??? email ph???i c?? ??t nh???t 6 k?? t???',
									Validator\StringLength::TOO_LONG => '?????a ch??? email ph???i c?? t???i ??a 128 k?? t???',
								],
							],
						],
						[
							'name' => Validator\Db\NoRecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);

		# filter and validate confirm_email input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'confirm_email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
						#['name' => Filter\StringToLower::class], as well as this one
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 6,
								'max' => 128,
								'messages' => [
									Validator\StringLength::TOO_SHORT => '?????a ch??? email ph???i c?? ??t nh???t 6 k?? t???',
									Validator\StringLength::TOO_LONG => '?????a ch??? email ph???i c?? t???i ??a 128 k?? t???',
								],
							],
						],
						[
							'name' => Validator\Db\NoRecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,
							],
						],
						[
							'name' => Validator\Identical::class,
							'options' => [
								'token' => 'email',  # field to compare against
								'messages' => [ 
									Validator\Identical::NOT_SAME => 'Email kh??ng tr??ng!',
								],
							],
						],
					],
				]
			)
		);

		# filter and validate birthday dateselect field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'birthday',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Date::class,
							'options' => [
								'format' => 'Y-m-d',
							],
						],
					],	
				]
			)
		);
		# filter and validate password input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'M???t kh???u ph???i c?? ??t nh???t 8 k?? t???',
									Validator\StringLength::TOO_LONG => 'M???t kh???u ph???i c?? nhi???u nh???t 25 k?? t???',
								],
							],
						],
					],
				]
			)
		);

		# filter and validate confirm_password field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'confirm_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'M???t kh???u ph???i c?? ??t nh???t 8 k?? t???',
									Validator\StringLength::TOO_LONG => 'M???t kh???u ph???i c?? nhi???u nh???t 25 k?? t???',
								],
							],
						],
						[
							'name' => Validator\Identical::class,
							'options' => [
								'token' => 'password',
								'messages' => [
									Validator\Identical::NOT_SAME => 'M???t kh???u kh??ng h???p l???!',
								],
							],
						],
					],
				]
			)
		);	
		# csrf field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! H??y ??i???n v??o n??o.',
								],
							],
						],
					],
				]
			)
		);

		return $inputFilter;	
	}

	public function getResetFormFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate password input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'new_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'M???t kh???u m???i ph???i c?? ??t nh???t 8 k?? t???',
									Validator\StringLength::TOO_LONG => 'M???t kh???u m???i ph???i c?? t???i ??a 25 k?? t???',
								],
							],
						],
					],
				]
			)
		);

		# filter and validate confirm_new_password field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'confirm_new_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'M???t kh???u m???i ph???i c?? ??t nh???t 8 k?? t???',
									Validator\StringLength::TOO_LONG => 'M???t kh???u m???i ph???i c?? t???i ??a 25 k?? t???',
								],
							],
						],
						[
							'name' => Validator\Identical::class,
							'options' => [
								'token' => 'new_password', 
								'messages' => [
									Validator\Identical::NOT_SAME => 'M???t kh???u kh??ng h???p l???!',
								],
							],
						],
					],
				]
			)
		);

		# csrf field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! Refill the form.',
								],
							],
						],
					],
				]
			)
		);

		return $inputFilter;
	}

	# sanitizes our loginForm
	public function getLoginFormFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate email 
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'email',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
						//['name' => Filter\StringToLower::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 6,  
								'max' => 128,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'Email ??t nh???t 6 k?? t???',
									Validator\StringLength::TOO_LONG => 'Email kh??ng qu?? 128 k?? t???',
								],
							],
						],
						['name' => Validator\EmailAddress::class],
						[
							'name' => Validator\Db\RecordExists::class,
							'options' => [
								'table' => $this->table,
								'field' => 'email',
								'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);

		# filter and validate password
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'M???t kh??ng kh??ng ??t h??n 8 k?? t???!',
									Validator\StringLength::TOO_LONG => 'M???t kh???u kh??ng qu?? 25 k?? t???',
								],
							],
						],
					],
				]
			)
		);

		# recall checkbox
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'recall',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class],  # removes html tags
						['name' => Filter\StringTrim::class],
						['name' => Filter\ToInt::class],
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						['name' => I18n\Validator\IsInt::class],
						[
							'name' => Validator\InArray::class,
							'options' => [
								'haystack' => [0, 1]
							],
						],
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

	# sanitizes our PasswordForm
	public function getPasswordFormFilter() 
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate current password input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'current_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'New Password must have at least 8 characters',
									Validator\StringLength::TOO_LONG => 'New Password must have at most 25 characters',
								],
							],
						],
					],
				]
			)
		);

		#filter and validate new password input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'new_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'New Password must have at least 8 characters',
									Validator\StringLength::TOO_LONG => 'New Password must have at most 25 characters',
								],
							],
						],
					],
				]
			)
		);

		# filter and validate confirm_new_password field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'confirm_new_password',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class], 
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 8,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'New Password must have at least 8 characters',
									Validator\StringLength::TOO_LONG => 'New Password must have at most 25 characters',
								],
							],
						],
						[
							'name' => Validator\Identical::class,
							'options' => [
								'token' => 'new_password', 
								'messages' => [
									Validator\Identical::NOT_SAME => 'Passwords do not match!',
								],
							],
						],
					],
				]
			)
		);

		# csrf field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'csrf',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\Csrf::class,
							'options' => [
								'messages' => [
									Validator\Csrf::NOT_SAME => 'Oops! Refill the form.',
								],
							],
						],
					],
				]
			)
		);

		return $inputFilter;
	}

	public function getUsernameFormFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		$factory = new InputFilter\Factory();

		# filter and validate current_username input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'current_username',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
						['name' => I18n\Filter\Alnum::class], # allows only [a-zA-Z0-9] characters
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 2,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'T??n ng?????i d??ng ph???i c?? ??t nh???t 2 k?? t???',
									Validator\StringLength::TOO_LONG => 'T??n ng?????i d??ng ph???i c?? t???i ??a 25 k?? t???',
								],
							],
						], 
						[
							'name' => I18n\Validator\Alnum::class,
							'options' => [
								'messages' => [
									I18n\Validator\Alnum::NOT_ALNUM => 'T??n ng?????i d??ng ch??? ???????c bao g???m c??c k?? t??? ch??? v?? s???',
								],
							],
						],
						[
							'name' => Validator\Db\RecordExists::class,
							'options' => [
								'table' => $this->table, 
								'field' => 'username',
								'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);

		# filter and validate username input field
		$inputFilter->add(
			$factory->createInput(
				[
					'name' => 'new_username',
					'required' => true,
					'filters' => [
						['name' => Filter\StripTags::class], # stips html tags
						['name' => Filter\StringTrim::class], # removes empty spaces
						['name' => I18n\Filter\Alnum::class], # allows only [a-zA-Z0-9] characters
					],
					'validators' => [
						['name' => Validator\NotEmpty::class],
						[
							'name' => Validator\StringLength::class,
							'options' => [
								'min' => 2,
								'max' => 25,
								'messages' => [
									Validator\StringLength::TOO_SHORT => 'T??n ng?????i d??ng ph???i c?? ??t nh???t 2 k?? t???',
									Validator\StringLength::TOO_LONG => 'T??n ng?????i d??ng ph???i c?? t???i ??a 25 k?? t???',
								],
							],
						], 
						[
							'name' => I18n\Validator\Alnum::class,
							'options' => [
								'messages' => [
									I18n\Validator\Alnum::NOT_ALNUM => 'T??n ng?????i d??ng ch??? ???????c bao g???m c??c k?? t??? ch??? v?? s???',
								],
							],
						],
						[
							'name' => Validator\Db\NoRecordExists::class,
							'options' => [
								'table' => $this->table, 
								'field' => 'username',
								'adapter' => $this->adapter,
							],
						],
					],
				]
			)
		);

		return $inputFilter;
	}


	public function saveAccount(array $data)
	{
		$timeNow = date('Y-m-d H:i:s');
		$values = [
			'username' => ucfirst($data['username']),
			'email'    => mb_strtolower($data['email']),
			'password' => (new Bcrypt())->create($data['password']), # encrypt/hash password
			'birthday' => $data['birthday'],
			'gender'   => $data['gender'],
			'role_id'  => $this->assignRoleId(),
			'created_at'  => $timeNow,
			'updated_at' => $timeNow,
		];

		$sqlQuery = $this->sql->insert()->values($values); 
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	private function assignRoleId()
	{
		$sqlQuery = $this->sql->select()->where(['role_id' => 1]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);
		$handler  = $sqlStmt->execute()->current();

		return (!$handler) ? 1 : 2;
	}

	public function updatePassword(string $password, int $userId)
	{
		$values = [
			'password' => (new Bcrypt())->create($password),
			'updated_at' => date('Y-m-d H:i:s')
		];

		$sqlQuery = $this->sql->update()->set($values)->where(['id' => $userId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	public function updateUsername(string $username, int $userId)
	{
		$values = [
			'username' => ucfirst($username),
			'updated_at' => date('Y-m-d H:i:s')
		];

		$sqlQuery = $this->sql->update()->set($values)->where(['id' => $userId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}


}
