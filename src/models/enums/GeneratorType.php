<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for generator types (GeneradoPorType).
 */
class GeneratorType extends EnumHelper
{
    /**
     * Issuer (obliged to issue the cancelled invoice)
     */
    public const ISSUER = 'E';
    
    /**
     * Recipient
     */
    public const RECIPIENT = 'D';
    
    /**
     * Third party
     */
    public const THIRD_PARTY = 'T';
}
