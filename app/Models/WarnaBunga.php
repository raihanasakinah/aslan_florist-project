<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarnaBunga extends Model
{
    use HasFactory;

    
        public function parentWarnaBunga() {
            return $this->belongsTo('App\Models\WarnaBunga', 'parent_id')->select('id', 'warnabunga_name');
        }

        public function subWarnaBunga() {
            return $this->hasMany('App\Models\WarnaBunga', 'parent_id')->where('status', 1);
        }


        public static function warnabungaDetails($url) {
            $warnabungaDetails = WarnaBunga::select('id', 'parent_id', 'warnabunga_name', 'url', 'description', 'meta_title', 'meta_description', 'meta_keywords')->with([ // Constraining Eager Loads: https://laravel.com/docs/9.x/eloquent-relationships#constraining-eager-loads    // Subquery Where Clauses: https://laravel.com/docs/9.x/queries#subquery-where-clauses    // Advanced Subqueries: https://laravel.com/docs/9.x/eloquent#advanced-subqueries
                'subWarnaBunga' => function($query) {
                    $query->select('id', 'parent_id', 'warnabunga_name', 'url', 'description', 'meta_title', 'meta_description', 'meta_keywords'); // Important Note: It's a MUST to select 'id' even if you don't need it, because the relationship Foreign Key `product_id` depends on it, or else the `product` relationship would give you 'null'!
                }
            ])->where('url', $url)->first()->toArray();

            $catIds = array();
            $catIds[] = $warnabungaDetails['id'];

            if ($warnabungaDetails['parent_id'] == 0) {
                $breadcrumbs = '
                    <li class="is-marked"><a href="' . url($warnabungaDetails['url']) .'">' . $warnabungaDetails['warnabunga_name'] .'</a></li>
                ';
            } else {
                $parentWarnaBunga = WarnaBunga::select('warnabunga_name', 'url')->where('id', $warnabungaDetails['parent_id'])->first()->toArray();
                $breadcrumbs = '
                    <li class="has-separator"><a href="' . url($parentWarnaBunga['url'])  .'">' . $parentWarnaBunga['warnabunga_name']  . '</a></li>
                    <li class="is-marked"><a href="'     . url($warnabungaDetails['url']) .'">' . $warnabungaDetails['warnabunga_name'] . '</a></li>
                ';
            }


            foreach ($warnabungaDetails['sub_warnabunga'] as $key => $subcat) {
            }

            $resp = array(
                'catIds'          => $catIds,
                'warnabungaDetails' => $warnabungaDetails,
                'breadcrumbs'     => $breadcrumbs
            );


            return $resp;
        }



        // this method is called in admin\filters\filters.blade.php to be able to translate the filter cat_ids column to category names to show them in the table in filters.blade.php in the Admin Panel
        public static function getWarnaBungaName($warnabunga_id) {
            $getWarnaBungaName = WarnaBunga::select('warnabunga_name')->where('id', $warnabunga_id)->first();


            return $getWarnaBungaName->warnabunga_name;
        }

        // Note: We also prevent making orders of the products of the Categories that are disabled (`status` = 0) (whether the Category is a Child Category or a Parent Category (Root Category) is disabled) in admin/categories/categories.blade.php
        public static function getWarnaBungaStatus($warnabunga_id) {
            $getWarnaBungaStatus = WarnaBunga::select('status')->where('id', $warnabunga_id)->first();


            return $getWarnaBungaStatus->status;
        }
}
