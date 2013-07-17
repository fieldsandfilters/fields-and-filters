<?php
/**
 * @version     1.0.0
 * @package     com_fieldsandfilters
 * @copyright   Copyright (C) 2012 KES - Kulka Tomasz . All rights reserved.
 * @license     GNU General Public License version 3 or later; see License.txt
 * @author      KES - Kulka Tomasz <kes@kextensions.com> - http://www.kextensions.com
 */

// No direct access.
defined('_JEXEC') or die;

/**
* @since       1.1.0
*/
class FieldsandfiltersControllerRequest extends JControllerLegacy
{
        /**
	 * @since       1.1.0
	 */
        public function fields()
	{
                // Check for a valid token. If invalid, send a 403 with the error message.
		JSession::checkToken( 'request' ) OR die();
		
                $app 		        = JFactory::getApplication();
		$fieldID 	        = $app->input->get( 'fid', 0, 'int' );
		$extensionTypeID 	= $app->input->get( 'etid', 0, 'int' );
		
		if( ( $app->input->get( 'format' ) == 'raw' ) && $extensionID && $fieldID )
		{
                        // Load PluginExtensions Helper
                        if( !( $extension = FieldsandfiltersFactory::getPluginExtensions()->getExtensionsByIDPivot( 'extension_type_id', $extensionTypeID )->get( $extensionTypeID ) ) )
                        {
                                // $app->enqueueMessage( JText::_( JText::_( 'COM_FILEDSANDFILTERS_FILTERS_ERROR_EXTENSION_NOT_EXISTS' ), 'error' ) );
                        }
                        else if( !( $field = FieldsandfiltersFactory::getFields()->getFieldsByID( $extension->extension_type_id, $fieldID )->get( $fieldID ) ) )
                        {
                                // $app->enqueueMessage( JText::_( JText::_( 'COM_FILEDSANDFILTERS_FILTERS_ERROR_FIELD_NOT_EXISTS' ), 'error' ) );
                        }
                        else
                        {
				JPluginHelper::importPlugin( 'fieldsandfiltersTypes' );
				
                                $context = 'com_fieldsandfilters.fields.' . $field->field_type;
                                FieldsandfiltersFactory::getDispatcher()->trigger( 'onFieldsandfiltersFieldsRequestRaw', array( $context, $field ) );
                        }
		}
		
		$this->setRedirect( JURI::root( true ) );
	}
}

