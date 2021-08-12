<?php

declare(strict_types=1);

namespace News\Model\Entity;

class TheloaiEntity
{
	protected $id;
	protected $TenTheLoai;
	protected $TenKhongDau;
	protected $updated_at;
	protected $created_at;

	#roles table column
	protected $LoaiTin;


	public function getId()
	{
		return $this->id;
	}

	public function setId($id) 
	{
		$this->id = $id;
	}

	public function getTenTheLoai()
	{
		return $this->TenTheLoai;
	}

	public function setTenTheLoai($TenTheLoai)
	{
		$this->TenTheLoai = $TenTheLoai;
	}

	public function getTenKhongDau()
	{
		return $this->TenKhongDau;
	}

	public function setTenKhongDau($TenKhongDau)
	{
		$this->TenKhongDau = $TenKhongDau;
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

	public function getLoaiTin()
	{
		return $this->LoaiTin;
	}

	public function setLoaiTin($LoaiTin)
	{
		$this->LoaiTin = $LoaiTin;
	}



}
