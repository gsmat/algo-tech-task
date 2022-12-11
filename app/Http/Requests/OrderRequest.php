<?php

namespace App\Http\Requests;

use App\Models\Bond;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class OrderRequest extends FormRequest
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
     * @param Request $request
     * @return string|array
     */
    public function rules(Request $request): string|array
    {
        try {
            $bondDetail = Bond::findOrFail($request->id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 404);
        }
        return [
            'order_date' => ['required', 'date', 'after_or_equal:' . $bondDetail->issue_date . '|before_or_equal:' . $bondDetail->last_circulation_date,],
            'bonds_quantity' => ['required', 'integer']
        ];
    }
}
