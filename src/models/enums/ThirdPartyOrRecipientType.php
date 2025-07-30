<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for third party or recipient types (TercerosODestinatarioType).
 */
class ThirdPartyOrRecipientType extends EnumHelper
{
    /**
     * Recipient
     */
    public const RECIPIENT = 'D';
    
    /**
     * Third party
     */
    public const THIRD_PARTY = 'T';
}
