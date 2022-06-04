<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Trix;

use Arsenaltech\NovaTab\NovaTab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\TabsOnEdit;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\ActionsInTabs;

class Blog extends Resource
{

    use TabsOnEdit;
    /**
    * The model the resource corresponds to.
    *
    * @var string
    */
    public static $model = 'App\Models\Blog';

    /**
    * The single value that should be used to represent the resource when being displayed.
    *
    * @var string
    */
    public static $title = 'id';

    /**
    * The columns that should be searched.
    *
    * @var array
    */
    public static $search = [
        'id',
    ];

    /**
    * Get the fields displayed by the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function fields(Request $request)
    {
        return [
            new Tabs('Blog Details', [
                'Settings'=>[
                    ID::make()->sortable(),

                    Text::make('Slug')->rules('required', 'max:191'),

                    Boolean::make('Active'),
                    Image::make('Image')->preview(function ($value, $disk) {
                        return $value
                        ? 'https://api.c1k.world/storage/'.$value
                        : null;
                    })->thumbnail(function ($value, $disk) {
                        return $value
                        ? 'https://api.c1k.world/storage/'.$value
                        : null;
                    }),
                    Text::make('Url','url_href')->hideFromIndex()->rules('required', 'max:191'),
                ],
                'RU' =>[
                    Text::make('Meta: title','meta_title_ru')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Meta: desc','meta_description_ru')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Title','title_ru')->rules('required', 'max:191'),
                    Text::make('Url','url_title_ru')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Preview','short_text_ru')->rules('required', 'max:191'),
                    Trix::make('Text','text_ru')->alwaysShow()->hideFromIndex(),
                ],
                'UA' => [
                    Text::make('Meta: title','meta_title_ua')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Meta: desc','meta_description_ua')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Title','title_ua')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Url','url_title_ua')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Preview','short_text_ua')->rules('required', 'max:191'),
                    Trix::make('Text','text_ua')->alwaysShow()->hideFromIndex(),
                ],
                'EN' => [
                    Text::make('Meta: title','meta_title_en')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Meta: desc','meta_description_en')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Title','title_en')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Url','url_title_en')->hideFromIndex()->rules('required', 'max:191'),
                    Text::make('Preview','short_text_en')->rules('required', 'max:191'),
                    Trix::make('Text','text_en')->alwaysShow()->hideFromIndex(),
                ],
            ]),
        ];
    }

    /**
    * Get the cards available for the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function cards(Request $request)
    {
        return [];
    }

    /**
    * Get the filters available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function filters(Request $request)
    {
        return [];
    }

    /**
    * Get the lenses available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
    * Get the actions available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function actions(Request $request)
    {
        return [];
    }
}
