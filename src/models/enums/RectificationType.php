<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for rectification types (ClaveTipoRectificativaType).
 */
class RectificationType extends EnumHelper
{
    /**
     * Substitutive rectification
     */
    public const SUBSTITUTIVE = 'S';
    
    /**
     * Incremental rectification
     */
    public const INCREMENTAL = 'I';
}
