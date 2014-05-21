<?php
/**
 * @package     fieldsandfilters.plugin
 * @subpackage  fieldsandfilters_extension.content
 * @copyright   Copyright (C) 2012 KES - Kulka Tomasz . All rights reserved.
 * @license     GNU General Public License version 3 or later; see License.txt
 * @author      KES - Kulka Tomasz <kes@kextensions.com> - http://www.kextensions.com
 */

defined('_JEXEC') or die;

JLoader::import('com_content.models.featured', JPATH_SITE . '/components');

/**
 * @since       1.2.0
 */
class plgFieldsandfiltersExtensionsContentModelFeatured extends ContentModelFeatured
{
	/**
	 * Context string for the model type.  This is used to handle uniqueness
	 * when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $context = 'com_content.featured';

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string $ordering  An optional ordering field.
	 * @param   string $direction An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		parent::populateState($ordering, $direction);

		$params = JFactory::getApplication()->getParams('com_content');
		$this->setState('params', $params);

		// Process show_noauth parameter
		$this->setState('filter.access', !$params->get('show_noauth'));

		$limit = $params->get('num_leading_articles') + $params->get('num_intro_articles') + $params->get('num_links');
		$this->setState('list.limit', $limit);
		$this->setState('list.links', $params->get('num_links'));

		// check for category selection
		if ($params->get('featured_categories') && implode(',', $params->get('featured_categories')) == true)
		{
			$featuredCategories = $params->get('featured_categories');
			$this->setState('filter.frontpage.categories', $featuredCategories);
		}
	}

	/**
	 * @return  JDatabaseQuery
     *
     * Change Access level from `protected` to `public` for Joomla! 2.5.x. In Joomla! 3.x must be `protected`
	 */
	public function getListQuery()
	{
		// Create a new query object.
		$query = parent::getListQuery();

		// Filter Fieldsandfilters itemsID
		$itemsID      = (array) $this->getState('fieldsandfilters.itemsID');
		$emptyItemsID = $this->setState('fieldsandfilters.emptyItemsID', false);

		if (!empty($itemsID) && !$emptyItemsID)
		{
			JArrayHelper::toInteger($itemsID);
			$query->where($this->getDbo()->quoteName('a.id') . ' IN( ' . implode(',', $itemsID) . ')');
		}

		return $query;
	}

	/**
	 * @since       1.2.0
	 */
	public function getItemsID()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItemsID');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items ID.
		$query = clone $this->_getListQuery();
		$query->clear('select');
		$query->clear('order');
		$query->clear('group');

		$query->select('DISTINCT ' . $this->_db->quoteName('a.id'));
		$this->_db->setQuery($query);

		if (!($itemsID = $this->_db->loadColumn()))
		{
			$itemsID = array();
		}

		$this->setState('fieldsandfilters.itemsID', $itemsID);

		// Add the items to the internal cache.
		$this->cache[$store] = $itemsID;

		return $this->cache[$store];

	}

	/**
	 * Returns a record count for the query
	 *
	 * @param   string $query The query.
	 *
	 * @return  integer  Number of rows for query
	 *
	 * @since       1.2.0
	 */
	protected function _getListCount($query)
	{
		$rows = count($this->getItemsID());

		return $rows;
	}

	/**
	 * @since       1.2.0
	 */
	public function getContentItemsID()
	{
		$limit   = $this->getState('list.limit');
		$itemsID = array();

		if ($limit >= 0)
		{
			$itemsID = $this->getItemsID();
		}

		return $itemsID;
	}
}
