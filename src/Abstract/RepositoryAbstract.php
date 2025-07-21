<?php

namespace App\Abstract;

use App\Exception\NotFoundException;
use Pilot\Component\Sql\Core\Sql;
use Psr\Container\ContainerInterface;

abstract class RepositoryAbstract
{
    protected Sql $connection;
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->connection = $this->container->get(Sql::class);
    }

    /**
     * This PHP function retrieves an ID based on dictionary code, lookup table name, dealer code, and
     * code.
     *
     * @param string $diccTable
     * @param string $lkTable
     * @param string $dealerCode
     * @param string $code
     *
     * @throws NotFoundException
     *
     * @return int The function `getIdByDiccionaryCode` is returning an integer value, which is the
     * result of calling the `hub_dicc_resolve_fn` function with the provided parameters. If the
     * function call is successful and returns a value, that value is returned by the
     * `getIdByDiccionaryCode` function. If there is no result or an error occurs, the function will
     * return an exception.
     */
    public function getIdByDiccionaryCode(string $diccTable, string $lkTable, string $dealerCode, string $code): int
    {
        $this->connection->newLine("select public.hub_dicc_resolve_fn(
            '@dicc_table.', 
            '@lk_table.',
            '@dealer_code.',
            '@code.'
        );");
        $this->connection->bind('dicc_table', $diccTable);
        $this->connection->bind('lk_table', $lkTable);
        $this->connection->bind('dealer_code', $dealerCode);
        $this->connection->bind('code', $code);

        $result = $this->connection->query($this->connection->sql());
        $resp = $result[0] ?? [];

        if (empty($resp['hub_dicc_resolve_fn'])) {
            throw new NotFoundException('Error getting ID by dictionary code', [], 404, 'record_not_found');
        }

        return $resp['hub_dicc_resolve_fn'];
    }
}
