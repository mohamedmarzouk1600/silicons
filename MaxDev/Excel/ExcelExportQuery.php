<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 9/19/20 5:48 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Excel;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportQuery implements FromQuery, WithHeadings
{
    use Exportable;

    /**
     * @var Builder
     */
    public $DataQuery;

    /**
     * ExcelExportQuery constructor.
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->DataQuery = $query;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return $this->DataQuery;
    }

    public function headings(): array
    {
        //return (new \MaxDev\Excel\ExcelExportQuery(\MaxDev\Models\Admin::query()))->download('users.xlsx');
        $item = $this->DataQuery->first();
        return array_keys($item->getOriginal());
    }
}
