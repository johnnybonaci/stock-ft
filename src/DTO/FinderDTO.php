<?php

namespace App\DTO;

class FinderDTO
{
    public int $page = 1;
    public int $rowsPerPage = 25;
    public array $columns = [];
    public array $filters;
    public array $sorts;

    public function __construct(
        int $page = 1,
        int $rowsPerPage = 25,
        array $columns = [],
        array $filters = [],
        array $sorts = [],
    ) {
        $this->page = $page;
        $this->rowsPerPage = $rowsPerPage;
        $this->columns = $columns;
        $this->filters = $this->transformArrayToFilterDTOs($filters);
        $this->sorts = $this->transformArrayToSortDTOs($sorts);
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'rows_per_page' => $this->rowsPerPage,
            'columns' => $this->columns,
            'filters' => $this->filters,
            'sorts' => $this->sorts,
        ];
    }

    private function transformArrayToFilterDTOs(array $data): array
    {
        $dtos = [];
        foreach ($data as $item) {
            $dtos[] = new FilterDTO($item['field'], $item['operation'], $item['value']);
        }

        return $dtos;
    }

    private function transformArrayToSortDTOs(array $data): array
    {
        $dtos = [];
        foreach ($data as $item) {
            $dtos[] = new SortDTO($item['field'], $item['order']);
        }

        return $dtos;
    }
}
