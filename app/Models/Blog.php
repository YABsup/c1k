<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Nova\Actions\Actionable;

class Blog extends Model
{
    //
    use Actionable;


    public function getTitleAttribute()
    {
        $val_name = 'title_'.request()->lang;
        return $this->$val_name;
    }

    public function getMetaTitleAttribute()
    {
        $val_name = 'meta_title_'.request()->lang;
        return $this->$val_name;
    }
    public function getMetaDescriptionAttribute()
    {
        $val_name = 'meta_description_'.request()->lang;
        return $this->$val_name;
    }
    public function getUrlTitleAttribute()
    {
        $val_name = 'url_title_'.request()->lang;
        return $this->$val_name;
    }
    public function getTextAttribute()
    {
        $val_name = 'text_'.request()->lang;
        return $this->$val_name;
    }

    public function getShortTextAttribute()
    {
        $val_name = 'short_text_'.request()->lang;
        return $this->$val_name;
    }

    public function getImageHrefAttribute()
    {
        return $this->image ? 'https://api.c1k.world/storage/'.$this->image : null;
    }


}
