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

class ForgotTable extends AbstractTableGateway
{
	protected $adapter;          # adapter to use to connect to the database
	protected $table = 'forgot';  # our table. one we want to store data in 

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}

	# clears any tokens that are more than 3 days old from the forgot table
	public function clearOldTokens()
	{
		$sqlQuery = $this->sql->delete()->where(['created_at < ?' => date('Y-m-d H:i:s', time() - (3600 * 72))]);
		$sqlStmt = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	# delete token belonging to user with specified $userId
	public function deleteToken(int $userId)
	{
		$sqlQuery = $this->sql->delete()->where(['user_id' => $userId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

	public function fetchToken(string $token, int $userId)
	{
		$sqlQuery = $this->sql->select()->where(['token' => $token])->where(['user_id' => $userId]);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute()->current();
	}

	# generate token
	public function generateToken(int $length)
	{
		if($length < 8 || $length > 40) {
			throw new \LengthException('Token length must be in range 08-40.');
		}

		# allowed characters
		$chars = 'NaObPcQdReSfTgUhViWjXkYlZmAnBoCpDqErFsGtHuIvJwKxLyMz';
		$token = '';

		for($i = 0; $i < $length; $i++) {
			$random = rand(0, strlen($chars) - 1);
			$token .= substr($chars, $random, 1); 
		}

		return $token;
	}

	# saving the token
	public function saveToken(string $token, int $userId)
	{
		$values = [
			'user_id' => $userId,
			'token'   => $token,
			'created_at' => date('Y-m-d H:i:s')
		];

		$sqlQuery = $this->sql->insert()->values($values);
		$sqlStmt  = $this->sql->prepareStatementForSqlObject($sqlQuery);

		return $sqlStmt->execute();
	}

}












?>