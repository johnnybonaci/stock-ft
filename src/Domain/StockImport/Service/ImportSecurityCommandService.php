<?php

namespace App\Domain\StockImport\Service;

use App\Constant\CommandConstant;
use App\Exception\SecurityCommandException;
use Pilot\Component\Security\Security;

class ImportSecurityCommandService
{
    private Security $security;

    /**
     * The constructor.
     *
     * @param Security $security The security helper for backend
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Can create entity StockImport
     * Returns SecurityCommandException if user can't do the action.
     *
     * @param string $dsn Instance
     * @param int $userId User ID
     *
     * @return bool
     */
    public function canCreate(string $dsn, int $userId): bool
    {
        $this->security->setDsn($dsn)->setUserId($userId);
        if (!$this->security->hasCommand(CommandConstant::IMPORT_CREATE_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'StockImport'], 401, 'access_denied_create_record');
        }

        return true;
    }

    /**
     * Can read entity StockImport
     * Returns SecurityCommandException if user can't do the action.
     *
     * @param string $dsn Instance
     * @param int $userId User ID
     *
     * @return bool
     */
    public function canRead(string $dsn, int $userId): bool
    {
        $this->security->setDsn($dsn)->setUserId($userId);
        if (!$this->security->hasCommand(CommandConstant::IMPORT_READ_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'StockImport'], 401, 'access_denied_read_record');
        }

        return true;
    }

    /**
     * Can find entity StockImport
     * Returns SecurityCommandException if user can't do the action.
     *
     * @param string $dsn Instance
     * @param int $userId User ID
     *
     * @return bool
     */
    public function canFind(string $dsn, int $userId): bool
    {
        $this->security->setDsn($dsn)->setUserId($userId);
        if (!$this->security->hasCommand(CommandConstant::IMPORT_FIND_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'StockImport'], 401, 'access_denied_find_records');
        }

        return true;
    }
}
