<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {

        $menu_exchanges = \DB::select("
        SELECT DISTINCT `city_id`, `cities`.`name`
        FROM `pairs`,`cities`
        WHERE `cities`.`id`=`pairs`.`city_id`
        LIMIT 50
        ");


        $current_site_mode = Cache::get( 'site_mode' );
        $current_best_mode = Cache::get( 'best_mode' );
        $current_cash_mode = Cache::get( 'cash_mode' );

        if($current_site_mode != null)
        {
            if($current_site_mode == 'off')
            {
                $site_mode = array(route('admin.go_to_online'),__('admin.site_on'),'btn-success' );
            }else{
                $site_mode = array(route('admin.go_to_offline'),__('admin.site_off'),'btn-danger' );
            }
        }else{
            $site_mode = array(route('admin.go_to_offline'),__('admin.site_off'),'btn-danger' );
        }

        if($current_best_mode != null)
        {
            if($current_best_mode == 'off')
            {
                $best_mode = array(route('admin.best_online'),__('admin.best_on'),'btn-success' );
            }else{
                $best_mode = array(route('admin.best_offline'),__('admin.best_off'),'btn-danger' );
            }
        }else{
            $best_mode = array(route('admin.best_offline'),__('admin.best_off'),'btn-danger' );
        }

        if($current_cash_mode != null)
        {
            if($current_cash_mode == 'off')
            {
                $cash_mode = array(route('admin.cash_online'),__('admin.cash_on'),'btn-success' );
            }else{
                $cash_mode = array(route('admin.cash_offline'),__('admin.cash_off'),'btn-danger' );
            }
        }else{
            $cash_mode = array(route('admin.cash_offline'),__('admin.cash_off'),'btn-danger' );
        }


        $ip_logs = \App\UserIp::orderBy('id','DESC')->limit('50')->get();


        if(\Auth::user()->role == 'pr')
        {
            return view('admin.dashboard.index_pr');
        }elseif(\Auth::user()->role == 'superadmin'){
            return view('admin.dashboard.index_superadmin', compact('menu_exchanges','site_mode','best_mode', 'cash_mode', 'ip_logs'));
        }else{
            return view('admin.dashboard.index_admin', compact('menu_exchanges','site_mode','best_mode', 'cash_mode', 'ip_logs'));
        }

    }

    static public function go_to_online()
    {
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'site_mode';
        $log->desc = 'on';
        $log->save();

        Cache::forever('site_mode', 'on');
        return back();
    }
    static public function go_to_offline()
    {
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'site_mode';
        $log->desc = 'off';
        $log->save();
        Cache::forever('site_mode', 'off');
        return back();
    }

    static public function best_online()
    {
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'best_mode';
        $log->desc = 'on';
        $log->save();
        Cache::forever('best_mode', 'on');
        return back();
    }
    static public function best_offline()
    {
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'best_mode';
        $log->desc = 'off';
        $log->save();
        Cache::forever('best_mode', 'off');
        return back();
    }

    static public function cash_online()
    {
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'cash_mode';
        $log->desc = 'on';
        $log->save();
        Cache::forever('cash_mode', 'on');
        return back();
    }
    static public function cash_offline()
    {
        $log = new \App\Logging;
        $log->user_id = \Auth::user()->id;
        $log->event = 'cash_mode';
        $log->desc = 'off';
        $log->save();
        Cache::forever('cash_mode', 'off');
        return back();
    }
}
