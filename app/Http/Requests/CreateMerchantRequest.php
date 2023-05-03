<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UserIsAdmin;

class CreateMerchantRequest extends FormRequest
{
    /**
     * Determine if the merchant is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'merchant_name' => 'required|max:255',
            'admin_id' => ['required','exists:users,id', new UserIsAdmin()],
        ];
    }
}
