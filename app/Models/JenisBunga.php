<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBunga extends Model
{
    use HasFactory;


        public function parentJenisBunga() {
            return $this->belongsTo('App\Models\JenisBunga', 'parent_id')->select('id', 'jenisbunga_name');
        }

        public function subJenisBunga() {
            return $this->hasMany('App\Models\JenisBunga', 'parent_id')->where('status', 1);
        }


        public static function jenisbungaDetails($url) {
            $jenisbungaDetails = JenisBunga::select('id', 'parent_id', 'jenisbunga_name', 'url', 'description', 'meta_title', 'meta_description', 'meta_keywords')->with([ // Constraining Eager Loads: https://laravel.com/docs/9.x/eloquent-relationships#constraining-eager-loads    // Subquery Where Clauses: https://laravel.com/docs/9.x/queries#subquery-where-clauses    // Advanced Subqueries: https://laravel.com/docs/9.x/eloquent#advanced-subqueries
                'subJenisBunga' => function($query) {
                    $query->select('id', 'parent_id', 'jenisbunga_name', 'url', 'description', 'meta_title', 'meta_description', 'meta_keywords'); // Important Note: It's a MUST to select 'id' even if you don't need it, because the relationship Foreign Key `product_id` depends on it, or else the `product` relationship would give you 'null'!
                }
            ])->where('url', $url)->first()->toArray();

            $catIds = array();
            $catIds[] = $jenisbungaDetails['id'];

            if ($jenisbungaDetails['parent_id'] == 0) {
                $breadcrumbs = '
                    <li class="is-marked"><a href="' . url($jenisbungaDetails['url']) .'">' . $jenisbungaDetails['jenisbunga_name'] .'</a></li>
                ';
            } else {
                $parentJenisBunga = JenisBunga::select('jenisbunga_name', 'url')->where('id', $jenisbungaDetails['parent_id'])->first()->toArray();
                $breadcrumbs = '
                    <li class="has-separator"><a href="' . url($parentJenisBunga['url'])  .'">' . $parentJenisBunga['jenisbunga_name']  . '</a></li>
                    <li class="is-marked"><a href="'     . url($jenisbungaDetails['url']) .'">' . $jenisbungaDetails['jenisbunga_name'] . '</a></li>
                ';
            }


            foreach ($jenisbungaDetails['sub_jenisbunga'] as $key => $subcat) {
            }

            $resp = array(
                'catIds'          => $catIds,
                'jenisbungaDetails' => $jenisbungaDetails,
                'breadcrumbs'     => $breadcrumbs
            );


            return $resp;
        }



        // this method is called in admin\filters\filters.blade.php to be able to translate the filter cat_ids column to category names to show them in the table in filters.blade.php in the Admin Panel
        public static function getJenisBungaName($jenisbunga_id) {
            $getJenisBungaName = JenisBunga::select('jenisbunga_name')->where('id', $jenisbunga_id)->first();


            return $getJenisBungaName->jenisbunga_name;
        }

        // Note: We also prevent making orders of the products of the Categories that are disabled (`status` = 0) (whether the Category is a Child Category or a Parent Category (Root Category) is disabled) in admin/categories/categories.blade.php
        public static function getJenisBungaStatus($jenisbunga_id) {
            $getJenisBungaStatus = JenisBunga::select('status')->where('id', $jenisbunga_id)->first();


            return $getJenisBungaStatus->status;
        }
}
