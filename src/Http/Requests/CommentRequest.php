<?php

namespace Module\Comment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
        return [
            'name' => 'required',
            'content' => 'required',
            'email' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('comment::message.name'),
            'content' => __('comment::message.content'),
            'email' => __('comment::message.email'),
        ];
    }
}
