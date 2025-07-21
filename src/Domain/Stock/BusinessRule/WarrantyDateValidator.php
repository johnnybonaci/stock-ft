<?php

namespace App\Domain\Stock\BusinessRule;

use App\Abstract\BusinessRuleAbstract;
use App\Constant\DataTypeConstants;
use App\Domain\Stock\Data\StockData;
use App\Domain\Stock\Data\StockParameter;
use App\Exception\InvalidBusinessRulesException;

class WarrantyDateValidator extends BusinessRuleAbstract
{
    public function apply(StockData $data): StockData
    {
        $start_date = $data->getWarrantyStartDate();
        $end_date = $data->getWarrantyEndDate();

        // Si ambos están vacíos, la validación pasa ya que no son obligatorios
        if (empty($start_date) && empty($end_date)) {
            return parent::apply($data);
        }

        // Si uno está vacío y el otro no, la validación falla
        if ((empty($start_date) && !empty($end_date)) || (!empty($start_date) && empty($end_date))) {
            if (empty($start_date) && !empty($end_date)) {
                throw new InvalidBusinessRulesException(
                    sprintf(
                        '%s field not completed. It has to be fulfilled because %s field was submitted',
                        StockParameter::WARRANTY_START_DATE,
                        StockParameter::WARRANTY_END_DATE
                    )
                );
            }
            if (!empty($start_date) && empty($end_date)) {
                throw new InvalidBusinessRulesException(
                    sprintf(
                        '%s field not completed. It has to be fulfilled because %s field was submitted',
                        StockParameter::WARRANTY_END_DATE,
                        StockParameter::WARRANTY_START_DATE
                    )
                );
            }
        }

        $start_date_dt = \DateTime::createFromFormat(DataTypeConstants::DATE_DB_FORMAT, (string)$start_date);
        $end_date_dt = \DateTime::createFromFormat(DataTypeConstants::DATE_DB_FORMAT, (string)$end_date);

        if (!$start_date_dt) {
            throw new InvalidBusinessRulesException(
                sprintf('%s field not valid. Must be a valid date format (YYYY-MM-DD)', StockParameter::WARRANTY_START_DATE)
            );
        }
        if (!$end_date_dt) {
            throw new InvalidBusinessRulesException(
                sprintf('%s field not valid. Must be a valid date format (YYYY-MM-DD)', StockParameter::WARRANTY_END_DATE)
            );
        }

        // TODO: Comento estas validaciones porque no se si son necesarias.
        // Cuando se implemente el proyecto de Garantias, ver si es necesario descomentarlas.

        /*$now = new \DateTime();
        if ($start_date_dt < $now) {
            throw new InvalidBusinessRulesException(
                sprintf('%s field must be greater than the current date', StockParameter::DEALER_INVOICE_DATE)
            );
        }
        if ($end_date_dt < $now) {
            throw new InvalidBusinessRulesException(
                sprintf('%s field must be greater than the current date', StockParameter::DEALER_INVOICE_DATE)
            );
        }*/

        if ($start_date_dt > $end_date_dt) {
            throw new InvalidBusinessRulesException(
                sprintf('%s field must be less than the %s', StockParameter::WARRANTY_START_DATE, StockParameter::WARRANTY_END_DATE)
            );
        }

        $now = new \DateTime();

        if ($end_date_dt < $now) {
            throw new InvalidBusinessRulesException(
                sprintf('The current date must not be later than the (%s).', StockParameter::WARRANTY_END_DATE),
                                [],
                400,
                "E_400_022"
            );
        }
        
        if (!$this->canActivateWarranty($data)) {
            throw new InvalidBusinessRulesException(sprintf('Cannot activate warranty: the %s must be ACTIVE', StockParameter::STATUS_CODE),
                [],
                400,
                "E_400_007");
        }
        if ($end_date_dt < $now) {
            $data->setWarrantyIsActiveFlag(0);
        } else {
            $data->setWarrantyIsActiveFlag(1);
        }

        return parent::apply($data);
    }

    /**
     * Valida si se puede activar la garantía para un factory stock
     * 
     * @param StockData $data Datos del stock
     * @return bool True si se puede activar la garantía, false en caso contrario
     */
    private function canActivateWarranty(StockData $data): bool
    {
        // Verificar que el dealer_id esté presente y sea válido
        $dealerId = $data->getDealerId();
        if (!isset($dealerId) || $dealerId <= 0) {
            return false;
        }

        // Verificar que el status_id esté presente y sea igual a 1 (activo)
        $statusId = $data->getStatusId();
        if (!isset($statusId) || $statusId != 1) {
            return false;
        }

        return true;
    }
}