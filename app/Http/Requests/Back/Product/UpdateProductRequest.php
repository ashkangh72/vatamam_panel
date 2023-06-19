<?php

namespace App\Http\Requests\Back\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'title'        => 'required|string|max:191',
            'title_en'     => 'nullable|string|max:191',
            'sku'          => ["required","string","max:8",Rule::unique('products','sku')->ignore($this->sku,'sku')],
            'category_id'  => 'required|exists:categories,id',
            'image'        => 'image',
            'slug'         => "nullable|unique:products,slug," . $this->product->id,
            'publish_date' => 'nullable|date',
            'spec_type'    => 'required_with:specification_group',
            'categories'   => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'type'         => 'required|in:physical,download',
            'cart_min'     => 'nullable|integer',
            'cart_max'     => 'nullable|integer',
            'recommended_products'          => 'nullable|array',
            'recommended_products.*'        => 'exists:products,id',
        ];

        if ($this->input('type') == 'physical') {
            $rules = array_merge($rules, [
                'warehouse_id'          => 'required_if:type,physical|exists:warehouses,id',
                'weight'                => 'required|integer',
                'unit'                  => 'required|string',
                'prices'                => 'required_if:type,physical|array',
                'prices.*.price'        => 'required|integer|min:100',
                'prices.*.stock'        => 'required|integer',
                'prices.*.attributes'   => "required|array",
                'prices.*.attributes.*' => "nullable|exists:attributes,id",
                'prices.*.discount'     => 'nullable|min:0|max:100',
            ]);
        }

        if ($this->input('type') == 'download') {
            $rules = array_merge($rules, [
                'download_files'                => 'required_if:type,download|array',
                'download_files.*.title'        => 'required|string',
                'download_files.*.file'         => 'required_without:download_files.*.price_id|file',
                'download_files.*.price'        => 'required|min:0',
                'download_files.*.discount'     => 'nullable|min:0|max:100',
                'download_files.*.status'       => 'required|in:active,inactive',
                'download_files.*.price_id'     => [
                    'nullable', Rule::exists('prices', 'id')->where(function ($query) {
                        return $query->where('product_id', $this->product->id);
                    }),
                ],
            ]);
        }

        return $rules;
    }
}
