<?php

namespace App\Services;

use App\Repositories\PlotRegistryRepository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PlotRegistryParserService
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
     * @param string|array $cadastralString
     * @return array
     */
    public function cadastralStringToArray($cadString): array
    {
        if (is_array($cadString)) {
            return $cadString;
        }

        $cadArray = explode(',', $cadString);
        foreach ($cadArray as $key => $cadNumber) {
            $cadArray[$key] = trim($cadNumber);
        }
        return $cadArray;
    }

    /**
     * @param $cadNumbers
     * @return string|Collection
     */
    public function checkPlots($cadNumbers)
    {
        $cadNumbers = $this->cadastralStringToArray($cadNumbers);

        list($cadExistModels, $cadNumbersForCheck) = $this->getExistCadNums($cadNumbers);

        $response = $this->sendCheckRequest($cadNumbersForCheck);

        /* response failed */
        if ($response->status() !== 200) {
            return $response->json()['message'];
        }

        /* put new data to bd */
        $createdModels = $this->createNewPlotsWithResponseData($response->json());

        return $cadExistModels->merge($createdModels);
    }

    /**
     * @param array $responseData
     * @return mixed
     */
    protected function createNewPlotsWithResponseData(array $responseData)
    {
        return  DB::transaction(function () use ($responseData) {
            $createdModels = [];
            foreach ($responseData as $checkedPlot) {
                $createdModels[] = $this->plotRepository->create([
                    'cadastral_number' => $checkedPlot['number'],
                    'address' => $checkedPlot['data']['attrs']['address'],
                    'price' => $checkedPlot['data']['attrs']['cad_cost'],
                    'area' => $checkedPlot['data']['attrs']['area_value'],
                    '_links' => $checkedPlot['_links'],
                ]);
            }
            return $createdModels;
        });
    }

    /**
     * @param array $cadNumbers
     * @return array
     */
    public function getExistCadNums(array $cadNumbers) : array
    {
        $cadExistModels = $this->plotRepository->whereIn('cadastral_number', $cadNumbers)->get();
        $cadExistModels->each(function ($plot) use (&$cadNumbers) {
            $existKeys = array_keys($cadNumbers, $plot->cadastral_number);
            foreach ($existKeys as $key) {
                unset($cadNumbers[$key]);
            }
        });
        return [$cadExistModels, $cadNumbers];
    }

    /**
     * @param array $cadNumbers
     * @return Response
     */
    public function sendCheckRequest(array $cadNumbers): Response
    {
        return Http::timeout(60)->post('http://pkk.bigland.ru/api/test/plots', [
            'collection' => [
                'plots' => $cadNumbers
            ],
        ]);
    }
}
