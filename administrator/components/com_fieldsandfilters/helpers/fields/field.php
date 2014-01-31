<?php
/**
 * @version     1.1.1
 * @package     com_fieldsandfilters
 * @copyright   Copyright (C) 2012 KES - Kulka Tomasz . All rights reserved.
 * @license     GNU General Public License version 3 or later; see License.txt
 * @author      KES - Kulka Tomasz <kes@kextensions.com> - http://www.kextensions.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * FieldsandfiltersFieldsField
 *
 * @package     com_fieldsandfilters
 * @since       1.2.0
 */
class FieldsandfiltersFieldsField
{
	/**
	 * @since       1.2.0
	 **/
	const PREPARE_CONTENT_FIELDS = 1;

	/**
	 * @since       1.2.0
	 **/
	const PREPARE_CONTENT_SYSTEM = 2;

	/**
	 * @since       1.2.0
	 **/
	public static function preparationContentSimple($type, &$data, $context, $excluded = array(), JRegistry $params = null)
	{
		switch ($type)
		{
			case self::PREPARE_CONTENT_FIELDS :
				FieldsandfiltersFieldsHelper::preparationContent($data, $context, null, null, (array) $excluded );
				break;
			case self::PREPARE_CONTENT_SYSTEM :
				$data = JHtml::_('content.prepare', $data, $params, $context);
				break;
		}
	}

	/**
	 * @since       1.2.0
	 **/
	public static function preparationContent($preparationName, stdClass $field, $dataName, $context, $excluded = array(), JRegistry $params = null)
	{
		if (!($type = $field->params->get($preparationName, false)) || $field->params->get(self::_preparationName($preparationName), false))
		{
			return;
		}

		self::_preparetionConent($type, $field, $dataName, $context, null, $excluded, $params);
		$field->params->set(self::_preparationName($preparationName), true);
	}

	/**
	 * @since       1.2.0
	 **/
	public static function preparationContentValues($preparationName, stdClass $field, $context, $excluded = array(), JRegistry $params = null)
	{
		if (!($type = $field->params->get($preparationName, false)) || $field->params->get(self::_preparationName($preparationName), false))
		{
			return;
		}

		unset($field->values->_error);

		foreach($field->values AS $value)
		{
			self::_preparetionConent($type, $field, 'value', $context, $value, $excluded, $params);
		}

		$field->params->set(self::_preparationName($preparationName), true);
	}

	/**
	 * @since       1.2.0
	 **/
	protected static function _preparetionConent($type, stdClass $field, $dataName, $context, stdClass $other = null, $excluded = array(), JRegistry $params = null)
	{
		$object = is_null($other) ? $field : $other;

		if (!isset($object->$dataName))
		{
			return;
		}

		self::preparationContentSimple($type, $object->$dataName, $context, $excluded, $params);
	}

	/**
	 * @since       1.2.0
	 **/
	protected static function _preparationName($preparationName)
	{
		return 'is.'.str_replace('.', '_', $preparationName);
	}

	/**
	 * @since       1.2.0
	 **/
	public static function getLayout($type, $mode, JRegistry $params)
	{
		$layout = $params->get('type.'.$type.'_layout', 'default');

		if(!$params->get('is.'.$type.'_layout', false))
		{
			if (strpos($layout, ':') !== false)
			{
				$layout = str_replace(':', ':'.$mode.'/', $layout);
			}
			else
			{
				$layout = $mode.'/'.$layout;
			}

			$params->set('is.'.$type.'_layout', true);
			$params->set('type.'.$type.'_layout', $layout);
		}

		return $layout;
	}
}