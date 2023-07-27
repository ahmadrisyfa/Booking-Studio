<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST': {
                    return [
                        // 'services_id' => 'required|string',
                        'time_from' => 'required|unique:bookings|date_format:Y-m-d H:i',
                        'time_to' => 'required|unique:bookings|date_format:Y-m-d H:i',
                    ];
                }
            case 'PUT':
            case 'PATCH': {
                    return [
                        // 'services_id' => ['required', 'string'],
                        'time_from' => ['required', 'unique:bookings,time_from, ' . $this->route()->booking->id, 'date_format:Y-m-d H:i'],
                        'time_to' => ['required', 'unique:bookings,time_to, ' . $this->route()->booking->id, 'date_format:Y-m-d H:i'],
                    ];
                }
            default:
                break;
        }
    }
    public function messages()
    {
        return [
            'time_from.unique' => 'Jam mulai yang anda pilih sudah ada yang boking silahkan pilih jam lain',
            'time_to.unique' => 'Jam Berakhir yang anda pilih sudah ada yang boking silahkan pilih jam lain',
        ];
    }
}
