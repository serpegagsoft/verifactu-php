<?php
namespace eseperio\verifactu\models\enums;

/**
 * Enumeration for tax types (ImpuestoType).
 */
class TaxType extends EnumHelper
{
    /**
     * Value Added Tax (IVA)
     */
    public const IVA = '01';
    
    /**
     * Tax on Production, Services and Imports (IPSI) of Ceuta and Melilla
     */
    public const IPSI = '02';
    
    /**
     * General Indirect Tax of the Canary Islands (IGIC)
     */
    public const IGIC = '03';
    
    /**
     * Other taxes
     */
    public const OTHER = '05';
}
