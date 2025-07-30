<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for operation qualifications (CalificacionOperacionType).
 */
class OperationQualificationType extends EnumHelper
{
    /**
     * Subject and not exempt - without reverse charge
     */
    public const SUBJECT_NO_EXEMPT_NO_REVERSE = 'S1';
    
    /**
     * Subject and not exempt - with reverse charge
     */
    public const SUBJECT_NO_EXEMPT_REVERSE = 'S2';
    
    /**
     * Not subject (Article 7, 14, others)
     */
    public const NOT_SUBJECT_ARTICLE = 'N1';
    
    /**
     * Not subject due to location rules
     */
    public const NOT_SUBJECT_LOCATION = 'N2';
}
