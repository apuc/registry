<?php

namespace Tests\Unit;

use App\Models\PlotRegistry;
use App\Services\PlotRegistryParserService;
use App\Services\PlotRegistryService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PlotRegistryServiceTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var mixed
     */
    protected $plotParserService;

    protected $testCorrectCadNumsArray = [
        '69:27:0000022:1306',
        '69:27:0000022:1307'
    ];

    /**
     * @var string
     */
    protected $testCorrectCadNumsString = '69:27:0000022:1306, 69:27:0000022:1307';

    /**
     * @var string
     */
    protected $testUnCorrectCadNumsString = '69:27:0000022:1306, 69:27:0000022:1307, 123213213123'; //last incorrect


    /**
     * PlotRegistryServiceTest constructor.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct()
    {
        parent::__construct();
        $this->plotParserService = app()->make(PlotRegistryParserService::class);
    }

    public function testCorrectDataAsString()
    {
        $callWithCorrectData = $this->plotParserService->checkPlots($this->testCorrectCadNumsString);
        $this->assertTrue(($callWithCorrectData instanceof Collection));
    }

    public function testUnCorrectDataAsString()
    {
        $testUnCorrectCadNums = $this->plotParserService->checkPlots($this->testUnCorrectCadNumsString);
        $this->assertFalse(($testUnCorrectCadNums instanceof Collection));
    }

    public function testServiceCheckDataAsArray()
    {
        $callWithCorrectData = $this->plotParserService->checkPlots($this->testCorrectCadNumsArray);
        $this->assertTrue(($callWithCorrectData instanceof Collection));
    }

    public function testServicePutDataToDb()
    {
        $callWithCorrectData = $this->plotParserService->checkPlots($this->testCorrectCadNumsArray);
        $this->assertDatabaseHas(with(new PlotRegistry())->getTable(), [
            'cadastral_number' => $callWithCorrectData[0]->cadastral_number,
        ]);

        $this->assertDatabaseHas(with(new PlotRegistry())->getTable(), [
            'cadastral_number' => $callWithCorrectData[1]->cadastral_number,
        ]);
    }

    public function testServiceNotCallApiTwiceIfDataExist()
    {
        $this->plotParserService->checkPlots($this->testCorrectCadNumsArray);

        $notExistCadNum = '69:27:0000022:1308';
        $this->assertDatabaseMissing(with(new PlotRegistry())->getTable(), [
            'cadastral_number' => $notExistCadNum
        ]);

        $testCadNums = array_merge($this->testCorrectCadNumsArray, [$notExistCadNum]);

        list($cadExistModels, $cadNumbersForCheck) = $this->plotParserService->getExistCadNums($testCadNums);

        $this->assertCount(2, $cadExistModels);
        $this->assertCount(1, $cadNumbersForCheck);
    }
}
