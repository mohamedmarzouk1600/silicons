<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 9/19/20 5:48 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace MaxDev\Excel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExportView implements FromView, WithHeadings
{
    use Exportable;

    /**
     * @var Builder
     */
    public $DataQuery;
    /**
     * @var
     */
    public $view;


    /**
     * ExcelExportView constructor.
     * @param Builder $query
     * @param $view
     */
    public function __construct(Builder $query, $view='Excel.rows')
    {
        $this->DataQuery = $query;
        $this->view = $view;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view($this->view, [
            'rows' => $this->DataQuery->get(),
            'headings'=>$this->headings(),
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $item = $this->DataQuery->first();
        return array_keys($item->getOriginal());
    }
}
