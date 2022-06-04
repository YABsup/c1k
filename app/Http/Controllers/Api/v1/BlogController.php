<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Blog;

use App\Http\Resources\BlogResource;
use App\Http\Resources\BlogResourceCollection;
/**
* @group Blog
*
*/

class BlogController extends Controller
{

    /** Blog List
    *
    * @queryParam lang string Язык блога ru | ua | en
    */
    public function index( Request $request )
    {
        $data = Blog::whereActive(true)->orderBy('id','DESC')->paginate($request->per_page??15);

        return new BlogResourceCollection($data);
    }

    /** Blog show
    *
    * @pathParam slug string Slug блога
    * @queryParam lang string Язык блога ru | ua | en
    */
    public function show( Request $request, $slug )
    {

        $blog = Blog::whereActive(true)->whereSlug($slug)->first();

        $blog_resource = $blog ? new BlogResource( $blog ) : null;

        $data = [
            'blog'=>$blog_resource,
            'read_now'=>[ new BlogResource( Blog::inRandomOrder()->first() ), new BlogResource( Blog::inRandomOrder()->first() ), new BlogResource( Blog::inRandomOrder()->first() )],
            'interesting'=>[ new BlogResource( Blog::inRandomOrder()->first() ), new BlogResource( Blog::inRandomOrder()->first() ) ],
        ];

        return response()->json([ 'status'=>'success', 'data'=>$data]);
    }

}
