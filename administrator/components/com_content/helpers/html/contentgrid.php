<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

/**
 * Utility class for creating HTML Grids
 *
 * @package		Joomla
 * @subpackage	Content
 * @static
 */
class JHtmlContentGrid
{
	function author( $name, $filter_authorid )
	{
		$db			=& JFactory::getDBO();
		$query = 'SELECT c.created_by, u.name' .
				' FROM #__content AS c' .
				' INNER JOIN #__sections AS s ON s.id = c.sectionid' .
				' LEFT JOIN #__users AS u ON u.id = c.created_by' .
				' WHERE c.state <> -1' .
				' AND c.state <> -2' .
				' GROUP BY u.name' .
				' ORDER BY u.name';
		$authors[] = JHtml::_('select.option', '0', '- '.JText::_('Select Author').' -', 'created_by', 'name');
		$db->setQuery($query);
		$authors = array_merge($authors, $db->loadObjectList());
		return JHtml::_('select.genericlist',  $authors, $name, 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'created_by', 'name', $filter_authorid);
	}

	function category( $name, $filter_catid, $filter_sectionid = null )
	{
		$db			=& JFactory::getDBO();
		$cat_filter = null;
		if (($filter_sectionid !== null) && ($filter_sectionid >= 0)) {
			$cat_filter = ' WHERE cc.section = '. (int) $filter_sectionid;
		}
		$query = 'SELECT cc.id AS value, cc.title AS text, section' .
				' FROM #__categories AS cc' .
				' INNER JOIN #__sections AS s ON s.id = cc.section ' .
				$cat_filter .
				' ORDER BY s.ordering, cc.ordering';
		$categories[] = JHtml::_('select.option', '0', '- '.JText::_('Select Category').' -');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());
		return JHtml::_(
			'select.genericlist',
			$categories,
			$name,
			array(
				'list.attr' => 'class="inputbox" size="1" onchange="document.adminForm.submit( );"',
				'list.selected' => $filter_catid
			)
		);
	}
}
