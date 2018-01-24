<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/*
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use App\Category;
*/

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // bad idea i can't change response message by overriding the failedAuthorization method
        /*
        $authId = Auth::user()->id;
        $categoryOwner = Category::find(request()->route('id'))->user_id;
        if ($authId != $categoryOwner) {
            return false;
        }
        */
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required'
        ];
    }

    //  i can't overriding this method
    /*
    public function failedAuthorization(
    {
        $apiController = new ApiController();
        return $apiController->setStatusCode('403')->responseWithError('you cant access');
    }
    */
}
