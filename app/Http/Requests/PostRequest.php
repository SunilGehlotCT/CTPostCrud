<?php

namespace App\Http\Requests;

use Shamaseen\Repository\Utility\Request as Request;

/**
 * Class PostRequest.
 */
class PostRequest extends Request
{
    /**
     * Define all the global rules for this request here.
     *
     * @var array
     */
    protected array $rules = [
        
    ];

    /**
     * Define rules for the store method.
     */
    public function storeRules()
    {
        return [
            'title'   => 'required|string|max:255', // |unique:posts,title
            'content' => 'required|string',
            'image'   => 'nullable|mimes:jpg,jpeg,png,gif|file|max:5120',
        ];
    }

    /**
     * Define rules for the update method.
     */
    public function updateRules()
    {
        return [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|mimes:jpg,jpeg,png,gif|file|max:5120',
        ];
    }

    // Write your methods using {Controller Method Name}Rules, or {HTTP Method}MethodRules syntax.
    // For example, when index method in the controller is called a method called indexRules will be triggered here if it is exists.
}
