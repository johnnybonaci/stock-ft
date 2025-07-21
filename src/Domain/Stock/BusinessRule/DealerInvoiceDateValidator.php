<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\DataTypeConstants;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;

class DealerInvoiceDateValidator extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        if (!empty($data->getDealerInvoiceDate())) {
            $invoiceDateDT = \DateTime::createFromFormat(DataTypeConstants::DATE_DB_FORMAT, $data->getDealerInvoiceDate());

            if (!$invoiceDateDT) {
                throw new InvalidBusinessRulesException(
                    sprintf('%s field not valid. Must be a valid date format (YYYY-MM-DD)', StockParameter::DEALER_INVOICE_DATE)
                );
            }

            $now = new \DateTime();

            if ($invoiceDateDT > $now) {
                throw new InvalidBusinessRulesException(
                    sprintf('%s field must be less than the current date', StockParameter::DEALER_INVOICE_DATE)
                );
            }
        }

        return parent::apply($data);
    }
}
