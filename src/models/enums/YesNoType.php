<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for yes/no values (SiNoType).
 * Original schema: SiNoType
 * @see docs/aeat/esquemas/SuministroInformacion.xsd.xml
 */
class YesNoType extends EnumHelper
{
    /**
     * Yes
     */
    public const YES = 'S';
    
    /**
     * No
     */
    public const NO = 'N';
}
