<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'slug'=>$this->slug,
            'title'=>$this->title,
            'image'=>$this->image_href,
            'meta'=>[
                'title'=>$this->meta_title,
                'description'=>$this->meta_description,
            ],
            'url'=>[
                'title'=>$this->url_title,
                'href'=>$this->url_href,
            ],
            'preview'=>$this->short_text,
            'text'=>$this->text,
            'date'=>$this->created_at->format('d.m.Y'),
            'time'=>$this->created_at->format('H:i'),
        ];
//        return parent::toArray($request);
    }
}
