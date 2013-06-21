<?php
/**
 * @version     1.0.0
 * @package     fieldsandfilters.plugin
 * @subpackage  fieldsandfilters_field_type.checkbox
 * @copyright   Copyright (C) 2012 KES - Kulka Tomasz . All rights reserved.
 * @license     GNU General Public License version 3 or later; see License.txt
 * @author      KES - Kulka Tomasz <kes@kextensions.com> - http://www.kextensions.com
 */

defined( '_JEXEC' ) or die;


$field                  = $plugin->field;
$values                 = $field->values;
$enableDescription      = $field->params->get( 'base.site_enabled_description', 0 );
$typeDescription        = $field->params->get( 'base.site_description_type', 0 );
$positionDescription    = $field->params->get( 'base.site_description_position', 0 );

$id = 'faf-filters-' . $field->field_id;
?>

<fieldset id="<?php echo $id; ?>" class="faf-filters faf-filters-checkboxlist <?php echo htmlspecialchars( $field->params->get( 'base.class', '' ) ); ?>">
        <?php if( $field->params->get( 'base.show_name', 1 ) ) :
                
                $attribsDiv = array( 'class' => 'faf-name' );
                
                if( $enableDescription && $typeDescription == 'tip' && !empty( $field->description ) )
                {
                        JHtml::_( 'behavior.tooltip', '.faf-hasTip' );
                        $attribsDiv['class'] = $attribsDiv['class'] . ' faf-hasTip';
                        $attribsDiv['title'] = htmlspecialchars( trim( $field->field_name, ':' ) . '::' . $field->description, ENT_COMPAT, 'UTF-8' );
                        
                }   
        ?>
        
        <legend <?php echo JArrayHelper::toString( $attribsDiv ); ?>>
                <?php echo htmlspecialchars( $field->field_name, ENT_QUOTES, 'UTF-8' ); ?>
        </legend>
        <?php endif; ?>
                
        <?php if( $enableDescription && $typeDescription == 'description' && $positionDescription == 'before' && !empty( $field->description ) ) : ?>
        <div class="faf-description">
                <?php echo $field->description; ?> 
        </div>
        <?php endif; ?>
        
        <?php foreach( $values AS &$value ) : ?>
        <div class="control-group faf-control-group">
                <label for="<?php echo ( $id . ' - ' . $value->field_value_id ); ?>" class="checkbox">
                        <input type="checkbox" name="fieldsandfilters[<?php echo $field->field_id; ?>][]" id="<?php echo ( $id . '-' . $value->field_value_id ); ?>"
                                class="faf-filters-input inputbox" value="<?php echo $value->field_value_id; ?>" data-ordering="<?php echo $value->ordering; ?>"
                                data-alias="<?php echo htmlspecialchars( $value->field_value_alias ); ?>" />
                        <?php echo htmlspecialchars( $value->field_value ); ?>
                        <span class="faf-filters-count badge"></span>
                </label>
        </div>
        <?php endforeach; ?>
        
        
        <?php if( $enableDescription && $typeDescription == 'description' && $positionDescription == 'after' && !empty( $field->description ) ) : ?>
        <div class="faf-description">
                <?php echo $field->description; ?> 
        </div>
        <?php endif; ?>
</fieldset>