<?php

namespace App\Services;

use App\Repositories\PlotRegistryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PlotRegistryService
{
    /**
     * @var PlotRegistryRepository
     */
    protected $plotRepository;

    /**
     * PlotRegistryService constructor.
     * @param PlotRegistryRepository $plotRepository
     */
    public function __construct(PlotRegistryRepository $plotRepository)
    {
        $this->plotRepository = $plotRepository;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function webIndexData(Request $request)
    {
        if ($request->has('cad_ids')) {
            return $this->plotRepository->searchData($request->cad_ids)->get();
        }
        return false;
    }

    /**
     * @param Collection $plots
     * @return array
     */
    public function getPlotsIds(Collection $plots) : array
    {
        $cadIds = [];
        $plots->each(function ($plot) use (&$cadIds) {
            $cadIds[] = $plot->id;
        });
        return $cadIds;
    }
}
