<?php

namespace App\Domain\Stock\Service;

use App\Constant\CommandConstant;
use App\Exception\SecurityCommandException;
use Pilot\Component\Security\Security;

class BackendSecurityCommandService
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
     * Can create entity Stock
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
        if (!$this->security->hasCommand(CommandConstant::CREATE_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'Stock'], 401, 'access_denied_create_record');
        }

        return true;
    }

    /**
     * Can update entity Stock
     * Returns SecurityCommandException if user can't do the action.
     *
     * @param string $dsn Instance
     * @param int $userId User ID
     *
     * @return bool
     */
    public function canUpdate(string $dsn, int $userId): bool
    {
        $this->security->setDsn($dsn)->setUserId($userId);
        if (!$this->security->hasCommand(CommandConstant::UPDATE_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'Stock'], 401, 'access_denied_update_record');
        }

        return true;
    }

    /**
     * Can delete entity Stock
     * Returns SecurityCommandException if user can't do the action.
     *
     * @param string $dsn Instance
     * @param int $userId User ID
     *
     * @return bool
     */
    public function canDelete(string $dsn, int $userId): bool
    {
        $this->security->setDsn($dsn)->setUserId($userId);
        if (!$this->security->hasCommand(CommandConstant::DELETE_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'Stock'], 401, 'access_denied_delete_record');
        }

        return true;
    }

    /**
     * Can read entity Stock
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
        if (!$this->security->hasCommand(CommandConstant::READ_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'Stock'], 401, 'access_denied_read_record');
        }

        return true;
    }

    /**
     * Can find entity Stock
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
        if (!$this->security->hasCommand(CommandConstant::FIND_COMMAND)) {
            throw new SecurityCommandException('You do not have permission to perform this action', ['entity' => 'Stock'], 401, 'access_denied_find_records');
        }

        return true;
    }
}
