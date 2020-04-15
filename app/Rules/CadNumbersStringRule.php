<?php

namespace App\Rules;

use App\Http\Requests\PlotRegistryFormRequest;
use App\Services\PlotRegistryParserService;
use Illuminate\Contracts\Validation\Rule;

class CadNumbersStringRule implements Rule
{
    /**
     * @var mixed
     */
    protected $plotParserService;

    /**
     * @var PlotRegistryFormRequest|null
     */
    protected $request;

    /**
     * Create a new rule instance.
     * CadNumbersStringRule constructor.
     * @param PlotRegistryFormRequest|null $request
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(PlotRegistryFormRequest $request = null)
    {
        $this->request = $request;
        $this->plotParserService = app()->make(PlotRegistryParserService::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $cadNumbers = $this->plotParserService->cadastralStringToArray($value);
        $this->setRequestData($attribute, $value, $cadNumbers);
        return $this->checkCadNumsArray($cadNumbers);
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $newValue
     */
    public function setRequestData(string $attribute, $value, array $newValue)
    {
        if (is_object($this->request)) {
            $this->request->replace([
                $attribute => $newValue,
                $attribute . 'String' => $value,
            ]);
        }
    }

    /**
     * @param array $cadNumbers
     * @return bool
     */
    protected function checkCadNumsArray(array $cadNumbers): bool
    {
        $regexp = '/^(\d{2}:){2}\d{2,7}:\d{2,4}$/';
        foreach ($cadNumbers as $number) {
            if (preg_match($regexp, $number) !== 1) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Одно из значений не соответствует формату АА:ВВ:CCCCСCC:КККК';
    }
}
