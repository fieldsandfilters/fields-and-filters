<?php
/**
* @package     Fieldsandfilters
* @copyright   Copyright (C) 2012 KES - Kulka Tomasz . All rights reserved.
* @license     GNU General Public License version 3 or later; see License.txt
* @author      KES - Kulka Tomasz <kes@kextensions.com> - http://www.kextensions.com
*/

namespace Fieldsandfilters\Field;

use Kextensions\Object\Object;
use Fieldsandfilters\Content\AbstractContentType;

defined('_JEXEC') or die;

/**
 * Abstract Base
 *
 * @package     Fieldsandfilters
 * @since       2.0
 */
abstract class AbstractBase extends Object implements BaseInterface
{
    /**
     * {@inheritdoc}
     */
    const _CLASS_ = __CLASS__;

    protected $contentType;

    protected $isField = false;

    protected $isFilter = false;

    protected $isStatic = false;

    /**
     * {@inheritdoc}
     */
    public function isField()
    {
        return $this->isField;
    }

    /**
     * {@inheritdoc}
     */
    public function isFilter()
    {
        return $this->isFilter;
    }

    /**
     * {@inheritdoc}
     */
    public function isStatic()
    {
        return $this->isStatic;
    }

    /**
     * {@inheritdoc}
     */
    public function setContentType(AbstractContentType $contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * {@inheritdoc}
     */
    function __toString()
    {
        return $this->render();
    }
}