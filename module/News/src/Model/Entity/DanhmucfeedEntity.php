<?php

declare(strict_types=1);

namespace News\Model\Entity;

class DanhmucfeedEntity
{
	protected $idDanhMuc;
	protected $TenDanhMuc;

	#roles table column
	protected $Link;


	public function getIdDanhMuc()
	{
		return $this->idDanhMuc;
	}

	public function setIdDanhMuc($idDanhMuc)
	{
		$this->idDanhMuc = $idDanhMuc;
	}

	public function getTenDanhMuc()
	{
		return $this->TenDanhMuc;
	}

	public function setTenDanhMuc($TenDanhMuc)
	{
		$this->TenDanhMuc = $TenDanhMuc;
	}

	public function getLink()
	{
		return $this->Link;
	}

	public function setLink($Link)
	{
		$this->Link = $Link;
	}


}
