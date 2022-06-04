<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray($request)
    {
        if( $this->file ){
            $file = url("public/docs/{$this->file}");
        } else{
            $file = '';
        }
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'file' => $file,
            'dateCreated' => $this->created_at
        ];
    }
}
