<?php

declare(strict_types=1);

namespace News\Model\Entity;

class UserEntity
{
	protected $id;
	protected $username;
	protected $email;
	protected $password;
	protected $birthday;
	protected $gender;
	protected $photo;
	protected $role_id;
	protected $active;
	protected $views;
	protected $created_at;
	protected $updated_at;
	#roles table column
	protected $role;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getBirthday()
	{
		return $this->birthday;
	}

	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

	public function getGender()
	{
		return $this->gender;
	}

	public function setGender($gender)
	{
		$this->gender = $gender;
	}

	public function getPhoto()
	{
		return $this->photo;
	}

	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}

	public function getRoleId()
	{
		return $this->role_id;
	}

	public function setRoleId($role_id)
	{
		$this->role_id = $role_id;
	}

	public function getActive()
	{
		return $this->active;
	}

	public function setActive($active)
	{
		$this->active = $active;
	}

	public function getCreated_at()
	{
		return $this->created_at;
	}

	public function setCreated_at($created_at)
	{
		$this->created_at = $created_at;
	}

	public function getUpdated_at()
	{
		return $this->updated_at;
	}

	public function setUpdated_at($updated_at)
	{
		$this->updated_at = $updated_at;
	}

	public function getRole()
	{
		return $this->role;
	}

	public function setRole($role)
	{
		$this->role  = $role;
	}
}
