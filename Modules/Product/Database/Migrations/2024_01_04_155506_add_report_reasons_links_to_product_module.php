<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\SidebarManager\Entities\Backendmenu;
use Modules\SidebarManager\Entities\BackendmenuUser;

class AddReportReasonsLinksToProductModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $this->getBackendMenu();

        $product_manage = DB::table('backendmenus')->where('name','product.product_manage')->first();
        $backendMenu = [
            "name" => "product.report_reasons",
            "route" => "product.report.index",
            "parent_id" => $product_manage->id,
            "is_admin" => 1,
        ];
        DB::table('backendmenus')->insert($backendMenu);
        $menu = DB::table('backendmenus')->where('name','product.report_reasons')->first();
        $parent = DB::table('backendmenus')->where('name','product.products')->first();

        $backendMenuUser = [
            "user_id" => 1,
            "parent_id" => $parent->id,
            "status" => 1,
            "backendmenu_id" => $menu->id
        ];
        DB::table('backendmenu_users')->insert($backendMenuUser);

        DB::table('permissions')->where('route','product.report.index')->delete();
        $lastId = DB::table('permissions')->max('id');
        $last_id = $lastId + 1;
        $main_menu = [
            [
                "id" => $last_id,
                'name' => "Report Reasons",
                "parent_id" => 175,
                'route' => "product.report.index",
                "status" => 2,
                "module_id" => 14,
                "type" => 2,
            ]
        ];
        DB::table('permissions')->insert($main_menu);
        $main_menuInserted = DB::table('permissions')->where('route','product.report.index')->first();
        if($main_menuInserted){
            $submenu = [
                [
                    "id" => $main_menuInserted->id + 1,
                    'name' => "Report Reasons",
                    "parent_id" => $main_menuInserted->id,
                    'route' => "product.report.index",
                    "status" => 2,
                    "module_id" => 14,
                    "type" => 3,
                ],
                [
                    "id" => $main_menuInserted->id + 2,
                    'name' => "Create",
                    "parent_id" => $main_menuInserted->id,
                    'route' => "product.report.store",
                    "status" => 2,
                    "module_id" => 14,
                    "type" => 3,
                ],
                [
                    "id" => $main_menuInserted->id + 3,
                    'name' => "Edit",
                    "parent_id" => $main_menuInserted->id,
                    'route' => "product.report.edit",
                    "status" => 2,
                    "module_id" => 14,
                    "type" => 3,
                ],
                [
                    "id" => $main_menuInserted->id + 4,
                    'name' => "Delete",
                    "parent_id" => $main_menuInserted->id,
                    'route' => "product.report.delete",
                    "status" => 2,
                    "module_id" => 14,
                    "type" => 3,
                ],
            ];
            DB::table('permissions')->insert($submenu);
        }

    }


    public function getBackendMenu()
    {
        $backend_menus = Backendmenu::where(function($q){
            $q->where('user_id',1)->orWhereNull('user_id');
        })->where('is_admin', 1)->get();

        $backendMenuUser = BackendmenuUser::with('backendMenu')->where('user_id', 1)->get();
        if($backendMenuUser->count() != $backend_menus->count()){

            $backend_menu_not_exsist = $backend_menus->whereNotIn('id', $backendMenuUser->pluck('backendmenu_id')->toArray());
            foreach($backend_menu_not_exsist as $menu){

                $parent_id = null;
                $position = 0;
                if($menu->parent_id){
                    $parentMenu = BackendmenuUser::where('backendmenu_id', $menu->parent_id)->where('user_id', 1)->first();
                    if($parentMenu){
                        $parent_id  = $parentMenu->id;
                        $position = BackendmenuUser::where('parent_id', $parent_id)->where('user_id', 1)->count() + 1;
                    }
                }

                BackendmenuUser::create(['parent_id' => $parent_id, 'user_id' => 1, 'backendmenu_id' => $menu->id, 'position' => $position]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $backendMenuMenu = DB::table('backendmenus')->where('name','product.report_reasons')->first();

        if($backendMenuMenu)
        {
            DB::table('backendmenu_users')->where('parent_id',$backendMenuMenu->id)->delete();
            DB::table('backendmenus')->where('name','product.report_reasons')->delete();
        }

        $main_menu = DB::table('permissions')->where('route','product.report.index')->first();
        if($main_menu)
        {
            DB::table('permissions')->where('parent_id', $main_menu->id)->delete();
            DB::table('permissions')->where('id', $main_menu->id)->delete();
        }
    }
}
