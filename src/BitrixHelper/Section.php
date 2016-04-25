<?php

namespace BitrixHelper;

class Section
{

	private $sectionId;

	public function getSectionId()
	{
		return $this->sectionId;
	}

	public function setSectionId($sectionId)
	{
		$this->sectionId = $sectionId;
	}

	private $section;

	private function setSection()
	{
		$res = \CIBlockSection::GetByID($this->getSectionId());
		$this->section = $res->GetNext();
	}

	public function getSection()
	{
		if (empty($this->section))
			$this->setSection();
		return $this->section;
	}

	/**
	 * Получаем родителей раздела
	 */
	public function getParents($getRoot = false)
	{
		$section = $this->getSection();
		$arFilter = array(
			'IBLOCK_ID' => $section['IBLOCK_ID'],
			'<LEFT_BORDER' => $section['LEFT_MARGIN'],
			'>RIGHT_BORDER' => $section['RIGHT_MARGIN']
		);
		if ($getRoot !== true) {
			$arFilter['>DEPTH_LEVEL'] = 1;
		}
		$rsSect = \CIBlockSection::GetList(array('left_margin' => 'desc'), $arFilter);
		$sections = array();
		while ($arSect = $rsSect->GetNext()) {
			$sections[] = $arSect;
		}
		return $sections;
	}
}