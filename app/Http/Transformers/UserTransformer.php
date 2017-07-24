<?php

namespace App\Http\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $avaliableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(User $item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'email' => ($item->email != "") ? $item->email : "default@localhost.com",
            'remember_token' => isset($item->remember_token) ? $item->remember_token : "---",
            'updated_at' => $item->updated_at,
            'created_at' => $item->created_at,
        ];
    }
}