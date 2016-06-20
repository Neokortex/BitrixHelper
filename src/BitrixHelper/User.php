<?php

namespace BitrixHelper;

class User
{

	private $userInfo;

	private function setUserInfo()
	{
		$rsUser = $this->BitrixUser->GetByID($this->getUserId());
		$arUser = $rsUser->Fetch();
		$arUser['FIO'] = trim($arUser['LAST_NAME'] . ' ' . $arUser['NAME'] . ' ' . $arUser['SECOND_NAME']);
		return $this->userInfo = $arUser;
	}

	public function getUserInfo($force = false)
	{
		if (empty($this->userInfo) || $force===true)
			$this->setUserInfo();
		return $this->userInfo;
	}

	private $BitrixUser;

	private function setBitrixUser()
	{
		global $USER;
		if (!is_object($USER))
			$USER = new \CUser;
		$this->BitrixUser = $USER;
	}

	private $userId;

	public function setUserId($userId = false)
	{
		if (!$userId) {
			$userId = $this->BitrixUser->GetID();
		}
		$this->userId = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function __construct($userId = false)
	{

		$this->setBitrixUser();
		$this->setUserId($userId);
	}
}