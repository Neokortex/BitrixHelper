<?php

namespace BitrixHelper;

use BitrixHelper\Utils;

class Form
{

	public function Errors()
	{
		$result = false;
		if ($errors = $this->getErrorsArray('ALL_ERRORS')) {
			$result = '<div class="alert alert-danger">' . implode('<br/>', $errors) . '</div>';
		}
		return $result;
	}

	public function Start(array $attr = array())
	{
		$arResult = $this->formArray;
		$attrText = Utils::getAttrText($attr);
		$result = str_replace('<form ', '<form ' . $attrText, $arResult["FORM_HEADER"]);
		return $result;
	}

	public function End()
	{
		$arResult = $this->formArray;
		$result = '<input type="hidden" name="web_form_submit" value="Сохранить" />';
		$result .= $arResult["FORM_FOOTER"];
		return $result;
	}

	public function Submit($title = 'Отправить', array $attr = array('class' => 'btn btn--default'), $value = 'Сохранить')
	{
		return '<button type="submit" ' . Utils::getAttrText($attr) . '>' . $title . '</button>';
	}

	public function Success($message = '', array $attr = array('class' => 'alert alert-success'))
	{
		$result = false;
		$arResult = $this->formArray;
		if ($note = $arResult['FORM_NOTE']) {
			if ($message) $note = $message;
			$result = '<div' . Utils::getAttrText($attr) . '>' . $note . '</div>';
		}

		return $result;
	}

	public function getErrorsArray($key = false)
	{
		$formInfo = $this->formArray;
		$errors_text = $formInfo['FORM_ERRORS'];
		$errors_text = strip_tags($errors_text, '<br>');
		$errors_array = explode('<br />', $errors_text);
		$errors = array('ALL_ERRORS' => array());
		foreach ($errors_array as $error) {
			$info = array(
				'CODE' => false,
				'MESSAGE' => false,
			);
			if (preg_match('/(email)/', $error)) {
				$info['CODE'] = 'email';
				$info['MESSAGE'] = $error;
				$errors['ALL_ERRORS'][] = $error;
			}
			if (preg_match('/(файл)/ui', $error)) {
				$info['CODE'] = 'file';
				$info['MESSAGE'] = $error;
				$errors['ALL_ERRORS'][] = $error;
			}
			if (preg_match('/(символы с картинки)/', $error)) {
				$info['CODE'] = 'captcha';
				$info['MESSAGE'] = $error;
				$errors['ALL_ERRORS'][] = $error;
			}
			if (preg_match('/(обязательные)/', $error)) {
				$info['CODE'] = 'required';
				$info['MESSAGE'] = $error;
			}
			if ($info['CODE'] and $info['MESSAGE']) {
				$errors[$info['CODE']] = $info;
			}
			if (preg_match('/&nbsp;&raquo;&nbsp;"(.+?)"/', $error, $matches)) {
				$errors['required']['FIELDS'][] = $matches[1];
				$errors['ALL_ERRORS'][] = 'Поле &laquo;' . $matches[1] . '&raquo; не заполнено';
			}
		}
		if ($key) {
			return $errors[$key];
		}
		return $errors;
	}

	public function Label($id, $name = false, array $attr = array())
	{
		$attrText = Utils::getAttrText($attr);
		$field = $this->formInfo['FIELDS'][$id];
		if (!$name)
			$name = $field['LABEL'];
		$result = '<label ' . $attrText . ' for="' . $field['NAME'] . '">' . $name . '</label>';
		return $result;
	}

	public function getQuestion($id)
	{
		$name = $this->formArray['FIELDS'][$id]['VARNAME'];
		return $this->formArray['QUESTIONS'][$name];
	}

	public function Widget($id, array $attr = array('class' => 'form-control'))
	{
		if (in_array($id, $this->printedFields)) return false;
		$question = $this->getQuestion($id);
		$formInfo = $this->getFormInfo();
		$field = $formInfo['FIELDS'][$id];
		unset($attr['name']);
		unset($attr['value']);
		if ($question['REQUIRED'] == 'Y') {
			$attr['required'] = 'required';
		}
		$attrText = Utils::getAttrText($attr);
		$widget = $question['HTML_CODE'];

		$widget = preg_replace('(class="(.+?)")', '', $widget);
		$widget = preg_replace('(size="(.+?)")', '', $widget);
		$widget = str_replace('<input ', '<input id="' . $field['NAME'] . '" ' . $attrText, $widget);
		$widget = str_replace('<textarea ', '<textarea id="' . $field['NAME'] . '" ' . $attrText, $widget);
		if ($question['STRUCTURE'][0]['FIELD_TYPE']=='email') {
			$widget = str_replace('type="text" ', 'type="email"' . $attrText, $widget);
		}
		$this->printedFields[] = $id;
		return $widget;
	}

	private $formInfo;

	private $printedFields;

	public function getFormInfo()
	{
		return $this->formInfo;
	}

	private function setFormInfo()
	{
		$arResult = $this->formArray;
		$fields = array();
		foreach ($arResult['arQuestions'] as $arQuestion) {
			$question = $arResult['QUESTIONS'][$arQuestion['VARNAME']];
			$arResult['FIELDS'][$arQuestion['ID']] = $arQuestion;
			preg_match('/name="(.+?)"/iu', $question['HTML_CODE'], $matches);
			$fieldName = $matches[1];
			$field['LABEL'] = $question['CAPTION'];
			$field['REQUIRED'] = $question['REQUIRED'];
			$field['NAME'] = $fieldName;
			$fields[$arQuestion['ID']] = $field;
		}
		$info = array(
			'FIELDS' => $fields,
		);
		$this->formArray = $arResult;
		$this->formInfo = $info;
	}

	private $formArray;

	public function __construct(array $formArray)
	{
		$this->formArray = $formArray;
		$this->setFormInfo();
	}
}