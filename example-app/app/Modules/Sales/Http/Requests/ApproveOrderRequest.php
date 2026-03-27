<?php
class ApproveOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('approve', $this->route('order'));
    }

    public function rules(): array
    {
        return [];
    }
}