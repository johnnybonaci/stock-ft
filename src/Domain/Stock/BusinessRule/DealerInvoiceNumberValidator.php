<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;

class DealerInvoiceNumberValidator extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        if (!empty($data->getDealerInvoiceDate()) && empty($data->getDealerInvoiceNumber())) {
            throw new InvalidBusinessRulesException(
                sprintf(
                    '%s field not completed. It has to be fulfilled because %s field was submitted',
                    StockParameter::DEALER_INVOICE_NUMBER,
                    StockParameter::DEALER_INVOICE_DATE
                )
            );
        }

        if (empty($data->getDealerInvoiceDate()) && !empty($data->getDealerInvoiceNumber())) {
            throw new InvalidBusinessRulesException(
                sprintf(
                    '%s field not completed. It has to be fulfilled because %s field was submitted',
                    StockParameter::DEALER_INVOICE_DATE,
                    StockParameter::DEALER_INVOICE_NUMBER
                )
            );
        }

        return parent::apply($data);
    }
}
