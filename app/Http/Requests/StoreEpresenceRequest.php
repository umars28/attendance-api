<?php

namespace App\Http\Requests;

use App\Rules\OneInOneOutPerDay;
use BenSampo\Enum\Rules\EnumValue;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Enums\EpresenceType;

class StoreEpresenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $userId = auth()->id();
        $type = $this->input('type');
        $waktu = $this->input('waktu');
    
        return [
            'type' => [
                'required',
                new EnumValue(EpresenceType::class)
            ],
            'waktu' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    if (empty($value)) return;
                    try {
                        $input = Carbon::createFromFormat('Y-m-d H:i:s', $value, config('app.timezone'));
                        $todayEnd = Carbon::now('Asia/Jakarta')->endOfDay();

                        if ($input->greaterThan($todayEnd)) {
                            $fail('The date must not be later than today');
                        }
                    } catch (Exception $e) {
                        $fail('Invalid time format');
                    }
                },
                new OneInOneOutPerDay($userId, $type, $waktu)
            ],
        ];
    }

   
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status'    => 'error',
            'message'   => 'The provided data is invalid',
            'errors'    => $validator->errors(),
        ], 422));
    }

}
