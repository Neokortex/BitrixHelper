<?php

namespace BitrixHelper;

class FormEvents
{
	public function OnAfterResultAdd($className, $methodName)
	{
		\AddEventHandler("form", "onAfterResultAdd", array($className, $methodName));
	}

	public function __construct()
	{
		\CModule::IncludeModule("form");
	}
}