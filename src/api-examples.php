<?php

/**
 * � ���� ����� ������� �������� ����� ������������� ������� ���� � ������� ������� ������������� api �������
 * ������������� ��������� ��� live template ��� phpStorm
 */

/**
 * �������� ������ ���������
 */
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID" => "", "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$count = $res->SelectedRowsCount(); // ���������� ���������� ������� �� �������
while ($ob = $res->GetNext()) {
	print_r($ob);
}

/**
 * ������ ��������
 */
$file = CFile::ResizeImageGet($file_id, array('width' => 150, 'height' => 150), BX_RESIZE_IMAGE_PROPORTIONAL, true); // BX_RESIZE_IMAGE_EXACT

/**
 * ���������� �������� � ���� ������
 */
$el = new CIBlockElement;
$arLoadProductArray = Array();
$arLoadProductArray["IBLOCK_ID"] = (int)"";
$arLoadProductArray["IBLOCK_SECTION_ID"] = (int)"";
$arLoadProductArray["NAME"] = "��������";
$arLoadProductArray["ACTIVE"] = "Y";
$arLoadProductArray["PREVIEW_TEXT"] = "";
$arLoadProductArray['PREVIEW_TEXT_TYPE'] = 'html'; // ��� ������ ��� PREVIEW_TEXT ����� ������� ��� �������������: text, html, editor
$arLoadProductArray["PROPERTY_VALUES"]["HTML_OR_TEXT"][0] = array("VALUE" => array("TEXT" => $html_text, "TYPE" => "html")); // ���������� �������� ���� HTML/�����
$arLoadProductArray["PROPERTY_VALUES"]["STATUS"] = "��������"; // ��������
if ($elementID = $el->Add($arLoadProductArray)) {
} else {
	echo "Error: " . $el->LAST_ERROR;
}

/**
 * ��������� ��������
 */
$el = new CIBlockElement;
$PROP = array();
$PROP[12] = "�����"; // �������� � ����� 12 ����������� �������� "�����"
$PROP[3] = 38; // �������� � ����� 3 ����������� �������� 38
$arLoadProductArray = Array(
	"MODIFIED_BY" => $USER->GetID(), // ������� ������� ������� �������������
	"IBLOCK_SECTION" => false, // ������� ����� � ����� �������
	"PROPERTY_VALUES" => $PROP,
	"NAME" => "�������",
	"ACTIVE" => "Y", // �������
	"PREVIEW_TEXT" => "����� ��� ������ ���������",
	"DETAIL_TEXT" => "����� ��� ���������� ���������",
	"DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/image.gif")
);
$PRODUCT_ID = 2; // �������� ������� � ����� (ID) 2
$res = $el->Update($PRODUCT_ID, $arLoadProductArray);