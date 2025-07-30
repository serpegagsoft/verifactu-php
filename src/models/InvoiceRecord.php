<?php

namespace eseperio\verifactu\models;

use eseperio\verifactu\models\enums\HashType;

/**
 * Abstract base class for invoice records (submissions and cancellations).
 * Contains common fields and validation rules for RegistroAlta and RegistroAnulacion.
 * Original schema: RegistroType
 * @see docs/aeat/esquemas/SuministroInformacion.xsd.xml
 */
abstract class InvoiceRecord extends Model
{
    /**
     * Version identifier (IDVersion)
     * Default value should match the XSD schema version in use.
     * @see docs/aeat/2025-06/esquemas/SuministroInformacion.xsd.xml
     * @var string
     */
    public $versionId = '1.0';

    /**
     * Invoice identification data (<IDFactura> or <IDFacturaAnulada>)
     * @var \eseperio\verifactu\models\InvoiceId
     */
    protected $invoiceId;

    /**
     * External reference (RefExterna, optional)
     * @var string
     */
    public $externalRef;

    /**
     * Chaining data (Encadenamiento), for hash linkage with previous record
     * @var \eseperio\verifactu\models\Chaining
     */
    protected $chaining;

    /**
     * System information (SistemaInformatico)
     * @var \eseperio\verifactu\models\ComputerSystem
     */
    protected $systemInfo;

    /**
     * Record timestamp with timezone (FechaHoraHusoGenRegistro)
     * @var string
     */
    public $recordTimestamp;

    /**
     * Hash type (TipoHuella)
     * Always SHA-256 as per AEAT specification.
     * @var \eseperio\verifactu\models\enums\HashType
     */
    public $hashType = HashType::SHA_256;

    /**
     * Record hash (Huella)
     * @var string
     */
    public $hash;

    /**
     * XML Signature block (optional, for signed XML)
     * @var string
     */
    public $xmlSignature;

    /**
     * Get the invoice ID
     * @return \eseperio\verifactu\models\InvoiceId
     */
    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    /**
     * Set the invoice ID
     * @param \eseperio\verifactu\models\InvoiceId|array $invoiceId InvoiceId object or array with issuerNif, seriesNumber, and issueDate
     * @return $this
     */
    public function setInvoiceId($invoiceId)
    {
        if (is_array($invoiceId)) {
            $id = new InvoiceId();
            $id->issuerNif = $invoiceId['issuerNif'] ?? null;
            $id->seriesNumber = $invoiceId['seriesNumber'] ?? null;
            $id->issueDate = $invoiceId['issueDate'] ?? null;
            $this->invoiceId = $id;
        } else {
            $this->invoiceId = $invoiceId;
        }
        return $this;
    }

    /**
     * Get the chaining data
     * @return \eseperio\verifactu\models\Chaining
     */
    public function getChaining()
    {
        return $this->chaining;
    }

    /**
     * Set the chaining data
     * @param \eseperio\verifactu\models\Chaining|array $chaining Chaining data
     * @return $this
     */
    public function setChaining($chaining)
    {
        if (is_array($chaining)) {
            if (isset($chaining['previousInvoice'])) {
                // Legacy format support
                $chainingObj = new Chaining();
                $previousInvoice = new PreviousInvoiceChaining();
                $previousInvoice->issuerNif = $chaining['previousInvoice']['issuerNif'] ?? null;
                $previousInvoice->seriesNumber = $chaining['previousInvoice']['seriesNumber'] ?? null;
                $previousInvoice->issueDate = $chaining['previousInvoice']['issueDate'] ?? null;
                $previousInvoice->hash = $chaining['previousHash'] ?? null;
                $chainingObj->setPreviousInvoice($previousInvoice);
                $this->chaining = $chainingObj;
            } elseif (isset($chaining['firstRecord']) && $chaining['firstRecord'] === 'S') {
                // First record in chain
                $chainingObj = new Chaining();
                $chainingObj->setAsFirstRecord();
                $this->chaining = $chainingObj;
            } else {
                // Array with previous invoice data
                $chainingObj = new Chaining();
                $chainingObj->setPreviousInvoice($chaining);
                $this->chaining = $chainingObj;
            }
        } else {
            $this->chaining = $chaining;
        }
        return $this;
    }

    /**
     * Set this as the first record in a chain
     * @return $this
     */
    public function setAsFirstRecord()
    {
        $chainingObj = new Chaining();
        $chainingObj->setAsFirstRecord();
        $this->chaining = $chainingObj;
        return $this;
    }

    /**
     * Get the system information
     * @return \eseperio\verifactu\models\ComputerSystem
     */
    public function getSystemInfo()
    {
        return $this->systemInfo;
    }

    /**
     * Set the system information
     * @param \eseperio\verifactu\models\ComputerSystem|array $systemInfo System information
     * @return $this
     */
    public function setSystemInfo($systemInfo)
    {
        if (is_array($systemInfo)) {
            if (isset($systemInfo['system']) && isset($systemInfo['version'])) {
                // Legacy format support
                $computerSystem = new ComputerSystem();
                $computerSystem->systemName = $systemInfo['system'];
                $computerSystem->version = $systemInfo['version'];
                // Set default values for required fields
                $computerSystem->providerName = $systemInfo['providerName'] ?? 'Default Provider';
                $computerSystem->systemId = $systemInfo['systemId'] ?? '01';
                $computerSystem->installationNumber = $systemInfo['installationNumber'] ?? '1';
                $computerSystem->onlyVerifactu = $systemInfo['onlyVerifactu'] ?? \eseperio\verifactu\models\enums\YesNoType::YES;
                $computerSystem->multipleObligations = $systemInfo['multipleObligations'] ?? \eseperio\verifactu\models\enums\YesNoType::NO;
                $computerSystem->hasMultipleObligations = $systemInfo['hasMultipleObligations'] ?? \eseperio\verifactu\models\enums\YesNoType::NO;

                // Set provider ID if available
                if (isset($systemInfo['providerId'])) {
                    $computerSystem->setProviderId($systemInfo['providerId']);
                } else {
                    // Create a default provider
                    $provider = new LegalPerson();
                    $provider->name = $systemInfo['providerName'] ?? 'Default Provider';
                    $provider->nif = $systemInfo['providerNif'] ?? '12345678Z';
                    $computerSystem->setProviderId($provider);
                }

                $this->systemInfo = $computerSystem;
            } else {
                // Array with system info data
                $computerSystem = new ComputerSystem();
                foreach ($systemInfo as $key => $value) {
                    if (property_exists($computerSystem, $key)) {
                        $computerSystem->$key = $value;
                    }
                }
                $this->systemInfo = $computerSystem;
            }
        } else {
            $this->systemInfo = $systemInfo;
        }
        return $this;
    }

    /**
     * Returns validation rules for the base invoice record.
     * Child classes should merge with their own rules.
     * @return array
     */
    public function rules()
    {
        return [
            [['versionId', 'invoiceId', 'chaining', 'systemInfo', 'recordTimestamp', 'hashType', 'hash'], 'required'],
            [['versionId', 'recordTimestamp', 'hash', 'externalRef', 'xmlSignature'], 'string'],
            ['invoiceId', function($value) {
                return ($value instanceof InvoiceId) ? true : 'Must be an instance of InvoiceId.';
            }],
            ['chaining', function($value) {
                return ($value instanceof Chaining) ? true : 'Must be an instance of Chaining.';
            }],
            ['systemInfo', function($value) {
                return ($value instanceof ComputerSystem) ? true : 'Must be an instance of ComputerSystem.';
            }],
            ['hashType', function($value) {
                return HashType::isValidValue($value) ? true : 'Must be an instance of HashType.';
            }],
            [['externalRef', 'xmlSignature'], function ($value) {
                return (is_null($value) || is_string($value)) ? true : 'Must be string or null.';
            }],
        ];
    }
}
