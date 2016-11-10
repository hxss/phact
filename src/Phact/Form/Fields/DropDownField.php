<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @company HashStudio
 * @site http://hashstudio.ru
 * @date 02/08/16 08:22
 */

namespace Phact\Form\Fields;

class DropDownField extends Field
{
    /**
     * @var string
     */
    public $inputTemplate = 'forms/field/dropdown/input.tpl';

    public $disabled = [];

    public $emptyText = null;
}