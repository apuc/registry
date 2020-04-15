<?php

namespace App\Console\Commands;

use App\Rules\CadNumbersStringRule;
use App\Services\PlotRegistryParserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CheckCadastral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:cadastral {numbers*}';
    protected $plotParserService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Проверка кадастровым номеров.';

    /**
     * Create a new command instance.
     *
     * @param PlotRegistryParserService $plotParserService
     */
    public function __construct(PlotRegistryParserService $plotParserService)
    {
        $this->plotParserService = $plotParserService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $cadNumbers = $this->argument('numbers');
        $this->validateArguments($cadNumbers) ? : die;
        $result = $this->plotParserService->checkPlots($cadNumbers);
        if (is_string($result)) {
            $this->error($result);
        } else {
            $this->showResultTable($result);
        }
    }

    /**
     * @param $cadNumbers
     * @return bool
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function validateArguments($cadNumbers) : bool
    {
        $validator = Validator::make([
            'cadastralNumber' => $cadNumbers
        ], [
            'cadastralNumber' => new CadNumbersStringRule()
        ]);
        if ($validator->fails()) {
            $this->info('Не пройдена валидация данных:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }
        return true;
    }

    /**
     * @param $result
     */
    protected function showResultTable($result)
    {
        $headers = ['Cadastral Number', 'Address', 'Price', 'Area'];

        $this->table($headers, $result);
    }
}
