<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlotRegistryApiResource;
use App\Models\PlotRegistry;
use App\Services\PlotRegistryService;

class PlotRegistryController extends Controller
{
    /**
     * @var PlotRegistryService
     */
    protected $plotService;

    /**
     * PlotRegistryController constructor.
     * @param PlotRegistryService $plotService
     */
    public function __construct(PlotRegistryService $plotService)
    {
        $this->plotService = $plotService;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PlotRegistryApiResource::collection(PlotRegistry::all());
    }
}
