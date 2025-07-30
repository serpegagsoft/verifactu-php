<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for invoice types (ClaveTipoFacturaType).
 */
class InvoiceType extends EnumHelper
{
    /**
     * Standard invoice (Art. 6, 7.2 and 7.3 of RD 1619/2012)
     */
    public const STANDARD = 'F1';
    
    /**
     * Simplified invoice and invoices without recipient identification (Art. 6.1.d of RD 1619/2012)
     */
    public const SIMPLIFIED = 'F2';
    
    /**
     * Invoice issued to replace previously declared simplified invoices
     */
    public const REPLACEMENT = 'F3';
    
    /**
     * Rectification invoice (Art. 80.1, 80.2 and error based on law)
     */
    public const RECTIFICATION_1 = 'R1';
    
    /**
     * Rectification invoice (Art. 80.3)
     */
    public const RECTIFICATION_2 = 'R2';
    
    /**
     * Rectification invoice (Art. 80.4)
     */
    public const RECTIFICATION_3 = 'R3';
    
    /**
     * Rectification invoice (Other cases)
     */
    public const RECTIFICATION_4 = 'R4';
    
    /**
     * Rectification invoice for simplified invoices
     */
    public const RECTIFICATION_SIMPLIFIED = 'R5';
}
