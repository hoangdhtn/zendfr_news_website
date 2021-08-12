<?php

declare(strict_types=1);

namespace News\Model\Entity;

class TintucEntity
{
	protected $id;
	protected $TieuDe;
	protected $TieuDeKhongDau;
	protected $TomTat;
	protected $NoiDung;
	protected $Hinh;
	protected $NoiBat;
	protected $SoLuotXem;
	protected $idTheLoai;
	protected $updated_at;
	protected $created_at;

	protected $TheLoai;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getTieuDe()
	{
		return $this->TieuDe;
	}

	public function setTieuDe($TieuDe)
	{
		$this->TieuDe = $TieuDe;
	}

	public function getTieuDeKhongDau()
	{
		return $this->TieuDeKhongDau;
	}

	public function setTieuDeKhongDau($TieuDeKhongDau)
	{
		$this->TieuDeKhongDau = $TieuDeKhongDau;
	}

	public function getTomTat()
	{
		return $this->TomTat;
	}

	public function setTomTat($TomTat)
	{
		$this->TomTat = $TomTat;
	}

	public function getNoiDung()
	{
		return $this->NoiDung;
	}

	public function setNoiDung($NoiDung)
	{
		$this->NoiDung = $NoiDung;
	}

	public function getHinh()
	{
		return $this->Hinh;
	}

	public function setHinh($Hinh)
	{
		$this->Hinh = $Hinh;
	}

	public function getNoiBat()
	{
		return $this->NoiBat;
	}

	public function setNoiBat($NoiBat)
	{
		$this->NoiBat = $NoiBat;
	}

	public function getSoLuotXem()
	{
		return $this->SoLuotXem;
	}

	public function setSoLuotXem($SoLuotXem)
	{
		$this->SoLuotXem = $SoLuotXem;
	}

	public function getIdTheLoai()
	{
		return $this->IdTheLoai;
	}

	public function setIdTheLoai($IdTheLoai)
	{
		$this->IdTheLoai = $IdTheLoai;
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

	public function getTheLoai()
	{
		return $this->TheLoai;
	}

	public function setTheLoai($TheLoai)
	{
		$this->TheLoai = $TheLoai;
	}


}
