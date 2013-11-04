<?php
/**
 * @version     1.1.1
 * @package     com_fieldsandfilters
 * @copyright   Copyright (C) 2012 KES - Kulka Tomasz . All rights reserved.
 * @license     GNU General Public License version 3 or later; see License.txt
 * @author      KES - Kulka Tomasz <kes@kextensions.com> - http://www.kextensions.com
 */

// no direct access
defined( '_JEXEC' ) or die;

JHtml::addIncludePath( JPATH_COMPONENT_ADMINISTRATOR . '/helpers/html' );
		

// Load Extensions Helper
$extensionsHelper = FieldsandfiltersFactory::getExtensions();

// Load PluginTypes Helper
$pluginTypesHelper 	= FieldsandfiltersFactory::getPluginTypes();
$valuesMode		= (array) $pluginTypesHelper->getMode( 'filter' );

// Load PluginExtensions Helper
$pluginExtensionsHelper = FieldsandfiltersFactory::getPluginExtensions();

JHtml::_( 'behavior.tooltip' );
JHTML::_( 'script','system/multiselect.js', false, true );

// Import CSS
JHtml::_( 'stylesheet', 'fieldsandfilters/component/fieldsandfilters_admin.css', array(), true );

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get( 'id' );
$listOrder	= $this->escape( $this->state->get( 'list.ordering' ) );
$listDirn	= $this->escape( $this->state->get( 'list.direction' ) );
$canOrder	= $user->authorise( 'core.edit.state', 'com_fieldsandfilters' );
$saveOrder	= $listOrder == 'f.ordering';

// Array of image, task, title, action
$states = array(
		1 => array(
				'unpublish',
				'JPUBLISHED',
				JText::_( 'JLIB_HTML_PUBLISH_ITEM' ) . '::' . JText::_( 'JLIB_HTML_UNPUBLISH_ITEM' ),
				'JPUBLISHED',
				true,
				'publish',
				'publish'
			),
		0 => array(
				'publish',
				'JUNPUBLISHED',
				JText::_( 'JLIB_HTML_UNPUBLISH_ITEM' ) . '::' . JText::_( 'JLIB_HTML_PUBLISH_ITEM' ),
				'JUNPUBLISHED',
				true,
				'unpublish',
				'unpublish'
			),
		-1 => array(
				'publish',
				'COM_FIELDSANDFILTERS_HTML_ONLYADMIN',
				JText::_( 'COM_FIELDSANDFILTERS_HTML_ONLYADMIN' ) . '::' . JText::_( 'JLIB_HTML_PUBLISH_ITEM' ),
				'COM_FIELDSANDFILTERS_HTML_ONLYADMIN',
				true,
				'onlyadmin',
				'onlyadmin'
			)
);

// Array of image, task, title, action
$required = array(
		0 => array(
				'required',
				'COM_FIELDSANDFILTERS_HTML_REQUIRED_ITEM',
				JText::_( 'COM_FIELDSANDFILTERS_HTML_UNREQUIRED_ITEM' ) . '::' . JText::_( 'COM_FIELDSANDFILTERS_HTML_REQUIRED_ITEM' ),
				'COM_FIELDSANDFILTERS_HTML_UNREQUIRED_ITEM',
				true,
				'unrequired',
				'unrequired'
			),
		1 => array(
				'unrequired',
				'COM_FIELDSANDFILTERS_HTML_UNREQUIRED_ITEM',
				JText::_( 'COM_FIELDSANDFILTERS_HTML_REQUIRED_ITEM' ) . '::' . JText::_( 'COM_FIELDSANDFILTERS_HTML_UNREQUIRED_ITEM' ),
				'COM_FIELDSANDFILTERS_HTML_REQUIRED_ITEM',
				true,
				'required',
				'required'
			)
);
?>


<form action="<?php echo JRoute::_( 'index.php?option=com_fieldsandfilters&view=fields' ); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_( 'JSEARCH_FILTER_LABEL' ); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape( $this->state->get( 'filter.search' ) ); ?>" title="<?php echo JText::_( 'Search' ); ?>" />
			<button type="submit"><?php echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?></button>
			<button type="button" onclick="document.id( 'filter_search' ).value='';this.form.submit();"><?php echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?></button>
		</div>
		
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_( 'JOPTION_SELECT_PUBLISHED' );?></option>
				<?php echo JHtml::_( 'select.options', JHtml::_( 'fieldsandfilters.publishedOptions' ), 'value', 'text', $this->state->get( 'filter.published' ), true);?>
			</select>
			
			<select name="filter_extension_type_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_( 'COM_FIELDSANDFILTERS_OPTION_SELECT_EXTENSION' );?></option>
				<?php echo JHtml::_( 'select.options', JHtml::_( 'fieldsandfilters.pluginExtensionsOptions' ), 'value', 'text', $this->state->get( 'filter.extension_type_id' ) );?>
			</select>

			<select name="filter_type" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_( 'COM_FIELDSANDFILTERS_OPTION_SELECT_TYPE' );?></option>
				<?php echo JHtml::_( 'select.options', JHtml::_( 'fieldsandfilters.pluginTypesOptions' ), 'value', 'text', $this->state->get( 'filter.type' ) );?>
			</select>
			
			<?php /*
			<select name="filter_access" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_( 'JOPTION_SELECT_ACCESS' );?></option>
				<?php echo JHtml::_( 'select.options', JHtml::_( 'access.assetgroups' ), 'value', 'text', $this->state->get( 'filter.access' ) );?>
			</select>

			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_( 'JOPTION_SELECT_LANGUAGE' );?></option>
				<?php echo JHtml::_( 'select.options', JHtml::_( 'contentlanguage.existing', true, true ), 'value', 'text', $this->state->get( 'filter.language' ) );?>
			</select>
			*/ ?>
		</div>
	</fieldset>
	<div class="clr"> </div>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
				</th>
				
				<th class='left'>
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_FIELD_NAME', 'f.field_name', $listDirn, $listOrder ); ?>
				</th>
				
				<td class="center">
					<?php echo JText::_( 'COM_FIELDSANDFILTERS_FIELDS_FIELD_VALUES' ); ?>
				</td>
				
				<th class="center">
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_FIELD_TYPE', 'f.field_type', $listDirn, $listOrder ); ?>
				</th>
				
				<th class="center">
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_EXTENSION_TYPE', 'f.extension_type_id', $listDirn, $listOrder ); ?>
				</th>
				
				<th class="center">
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_STATUS', 'f.state', $listDirn, $listOrder ); ?>
				</th>
				
				<th class="center">
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_REQUIRED', 'f.required', $listDirn, $listOrder ); ?>
				</th>
				
				<?php /*
				<th class="center">
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_LANGUAGE', 'f.language', $listDirn, $listOrder ); ?>
				</th>
				*/ ?>
				
				<th width="10%">
					<?php echo JHtml::_( 'grid.sort', 'JGRID_HEADING_ORDERING', 'f.ordering', $listDirn, $listOrder ); ?>
					<?php if( $canOrder && $saveOrder ) :?>
						<?php echo JHtml::_( 'grid.order', $this->items, 'filesave.png', 'filters.saveorder' ); ?>
					<?php endif; ?>
				</th>
				
				<th width="1%" class="nowrap">
					<?php echo JHtml::_( 'grid.sort', 'COM_FIELDSANDFILTERS_FIELDS_FIELD_ID', 'f.field_id', $listDirn, $listOrder ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach( $this->items as $i => $item ) :
			$ordering	= ( $listOrder == 'f.ordering' );
			$canCreate	= $user->authorise( 'core.create',		'com_fieldsandfilters' );
			$canEdit	= $user->authorise( 'core.edit',		'com_fieldsandfilters' );
			$canCheckin	= $user->authorise( 'core.manage',		'com_fieldsandfilters' );
			$canChange	= $user->authorise( 'core.edit.state',		'com_fieldsandfilters' );
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_( 'grid.id', $i, $item->field_id ); ?>
				</td>
				
				<td>
					<?php if( $canEdit ) : ?>
						<a href="<?php echo JRoute::_( 'index.php?option=com_fieldsandfilters&task=field.edit&id=' . (int) $item->field_id ); ?>">
							<?php echo $this->escape( $item->field_name ); ?>
						</a>
					<?php else : ?>
						<?php echo $this->escape( $item->field_name ); ?>
					<?php endif; ?>
					
					<?php if( in_array( $item->mode, $valuesMode ) ) : ?>
					<p class="smallsub">
						<?php echo JText::sprintf( 'JGLOBAL_LIST_ALIAS', $this->escape( $item->field_alias ) ); ?>
					</p>
					<?php endif; ?>
				</td>
				
				<td class="center">
					<?php if( in_array( $item->mode, $valuesMode ) ) : ?>
						<a href="<?php echo JRoute::_( 'index.php?option=com_fieldsandfilters&view=fieldvalues&id=' . (int) $item->field_id ); ?>">
							<?php echo JText::_( 'COM_FIELDSANDFILTERS_FIELDS_FIELD_VALUES' ); ?>
						</a>
					<?php endif; ?>
				</td>
				
				<td class="center">
					<?php if( $type = $pluginTypesHelper->getTypes( true )->get( $item->field_type ) ) : ?>
						<?php
							$extensionsHelper->loadLanguage( 'plg_' . $type->type . '_' . $type->name );
							$typeName 	= $pluginTypesHelper->getModeName( $item->mode, 'type' );
							$typeForm	= $type->forms->get( $typeName, new JObject );
							
							if( isset( $typeForm->group->title ) )
							{
								$titleType =  JText::_( $typeForm->title ) . ' [' . JText::_( $typeForm->group->title ) . ']';
							}
							else
							{
								$titleType = JText::_( $typeForm->title );
							}
						?>
						<?php echo $titleType; ?>
					<?php else: ?>
						<?php echo JText::_( 'JUNDEFINED' ); ?>
					<?php endif; ?>
				</td>
				
				<td class="center">
					<?php if( $extension = $pluginExtensionsHelper->getExtensionsPivot( 'extension_type_id', true )->get( (int) $item->extension_type_id ) ) : ?>
						<?php
						// load plugin language
							if( $extension->name != 'allextensions' )
							{
								$extensionsHelper->loadLanguage( 'plg_' . $extension->type . '_' . $extension->name, JPATH_ADMINISTRATOR  );
							}
							$extensionForm = $extension->forms->get( 'extension', new JObject );
						?>
						<?php echo JText::_( $extensionForm->get( 'title' ) ); ?>
					<?php else: ?>
						<?php echo JText::_( 'JUNDEFINED' ); ?>
					<?php endif; ?>
				</td>
				
				<td class="center">
					<?php echo JHtml::_( 'jgrid.state', $states, $item->state, $i, 'fields.', $canChange, false, 'cb' ); ?>
				</td>
				
				<td class="center">
					<?php echo JHtml::_( 'jgrid.state', $required, $item->required, $i, 'fields.', $canChange, false, 'cb' ); ?>
				</td>
				
				<?php /*
				<td class="center">
					<?php if( $item->language == '*' ): ?>
						<?php echo JText::alt( 'JALL', 'language' ); ?>
					<?php else:?>
						<?php echo $item->language_title ? $this->escape( $item->language_title ) : JText::_( 'JUNDEFINED' ); ?>
					<?php endif;?>
				</td>
				*/ ?>
				
				<td class="order">
					<?php if( $canChange) : ?>
						<?php if( $saveOrder) :?>
							<?php if( $listDirn == 'asc') : ?>
								<span>
									<?php echo $this->pagination->orderUpIcon( $i, true, 'fields.orderup', 'JLIB_HTML_MOVE_UP', $ordering ); ?>
								</span>
								<span>
									<?php echo $this->pagination->orderDownIcon( $i, $this->pagination->total, true, 'fields.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering ); ?>
								</span>
							<?php elseif( $listDirn == 'desc' ) : ?>
								<span>
									<?php echo $this->pagination->orderUpIcon( $i, true, 'fields.orderdown', 'JLIB_HTML_MOVE_UP', $ordering ); ?>
								</span>
								<span>
									<?php echo $this->pagination->orderDownIcon( $i, $this->pagination->total, true, 'fields.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering ); ?>
								</span>
							<?php endif; ?>
						<?php endif; ?>
						<?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />
					<?php else : ?>
						<?php echo (int) $item->ordering; ?>
					<?php endif; ?>
				</td>
				
				<td class="center">
					<?php echo (int) $item->field_id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
		
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_( 'form.token' ); ?>
</form>