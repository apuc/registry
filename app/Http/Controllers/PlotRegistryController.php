<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlotRegistryFormRequest;
use App\Services\PlotRegistryParserService;
use App\Services\PlotRegistryService;
use Illuminate\Http\Request;

class PlotRegistryController extends Controller
{
    /**
     * @var PlotRegistryService
     */
    protected $plotService;

    /**
     * @var PlotRegistryParserService
     */
    protected $plotParserService;

    /**
     * PlotRegistryController constructor.
     * @param PlotRegistryService $plotService
     * @param PlotRegistryParserService $plotParserService
     */
    public function __construct(PlotRegistryService $plotService, PlotRegistryParserService $plotParserService)
    {
        $this->plotService = $plotService;
        $this->plotParserService = $plotParserService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $searchResult = $this->plotService->webIndexData($request);
        $lastInput = session()->has('cadastral_input') ? session('cadastral_input') : '';
        return view('plot.index', [
            'lastInput' => $lastInput,
            'searchResult' => $searchResult
        ]);
    }

    /**
     * @param PlotRegistryFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function check(PlotRegistryFormRequest $request)
    {
        $cadastralNumber = $request->cadastralNumber;
        $cadastralNumberString = $request->cadastralNumberString;
        $result = $this->plotParserService->checkPlots($cadastralNumber);

        if (is_string($result)) {
            session()->flash('server-error', 'Ошибка обработки данных.');
            return redirect()->route('plot-registry.index');
        } else {
            session()->flash('cadastral_input', $cadastralNumberString);
            $cadIds = $this->plotService->getPlotsIds($result);
            return redirect()->route('plot-registry.index', ['cad_ids' => $cadIds]);
        }
    }
}
