<?php

namespace App\Constant;

final class DataTypeConstants
{
    public const DATETIME_ISO8601 = 'Y-m-d\TH:i:s';
    public const DATETIME_DB_FORMAT = 'Y-m-d H:i:s';
    public const DATE_DB_FORMAT = 'Y-m-d';

    // ExpReg para validar alfanumerico con guion medio
    public const VALID_CHASSIS_NUMBER_PATTERN = '/^[a-zA-Z0-9-]+$/';
    public const VALID_INVOICE_NUMBER_PATTERN = '/^[a-zA-Z0-9-]+$/';

    // ExpReg para validar alfanumerico con guiones y barras
    public const VALID_ENGINE_NUMBER_PATTERN = '/^[a-zA-Z0-9\-_\/]+$/';

    // ExpReg para validar formato fecha
    public const VALID_DATE_PATTERN = '/^\d{4}-\d{2}-\d{2}$/';
    public const VALID_DATE_WITH_EMPTY_PATTERN = '/^$|^\d{4}-\d{2}-\d{2}$/';
    // ExpReg para validar 0 y 1
    public const VALID_BOOL_PATTERN = '/^(1|0)$/i';

    // ExpReg para validar las longitudes de un texto.
    public const VALID_LENGTH_1_PATTERN = '/^.{0,1}$/';
    public const VALID_LENGTH_2_PATTERN = '/^.{0,2}$/';
    public const VALID_LENGTH_10_PATTERN = '/^.{0,10}$/';
    public const VALID_LENGTH_8_17_PATTERN = '/^.{8}$|^.{17}$/';
    public const VALID_LENGTH_20_PATTERN = '/^.{0,20}$/';
    public const VALID_LENGTH_32_PATTERN = '/^.{0,32}$/';
    public const VALID_LENGTH_36_PATTERN = '/^.{0,36}$/';
    public const VALID_LENGTH_50_PATTERN = '/^.{0,50}$/';
    public const VALID_LENGTH_100_PATTERN = '/^.{0,100}$/';
    public const VALID_LENGTH_150_PATTERN = '/^.{0,150}$/';
    public const VALID_LENGTH_200_PATTERN = '/^.{0,200}$/';
    public const VALID_LENGTH_250_PATTERN = '/^.{0,250}$/';
    public const VALID_LENGTH_300_PATTERN = '/^.{0,300}$/';
    public const VALID_LENGTH_500_PATTERN = '/^.{0,500}$/';

    public const VALID_GUID_PATTERN = '/^\{?[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}\}?$/';
}
