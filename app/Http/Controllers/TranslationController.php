<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TranslationController extends Controller
{
    //

/**
 * Change session locale
 * @param  Request $request
 * @return Response
 */
public function changeLocale(Request $request)
{
    $this->validate($request, ['language' => 'required|in:ru,en,uk']);

    \Session::put('locale', $request->language);

    return redirect()->back();
}

}
