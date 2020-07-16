<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identificador' =>  (int)$seller->id,
            'nombre' =>  (string)$seller->name,
            'correo' =>  (string)$seller->email,
            'verificado' =>  (int)$seller->verified,
            'fechaCreacion' =>  (string)$seller->created_at,
            'fechaActualizacion' => (string)$seller->updated_at,
            'fechaEliminacion' => isset($seller->updated_at) ? (string) $user->deleted_at : null,
        
        ];
    }
}
