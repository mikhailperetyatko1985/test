<?php

namespace App\Http\Requests;

use App\DTOs\AttachReferralData;
use Illuminate\Foundation\Http\FormRequest;

class AttachReferralRequest extends FormRequest
{
    /**
     * Авторизация в проекте заглушена (заголовок X-Master-Id), отдельной проверки прав нет.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('code'))) {
            $this->merge(['code' => trim((string) $this->input('code'))]);
        }
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:64'],
        ];
    }

    /**
     * Валидированный ввод в виде DTO, который контроллер передаёт в сервис.
     */
    public function toData(): AttachReferralData
    {
        return new AttachReferralData(code: (string) $this->validated('code'));
    }
}
