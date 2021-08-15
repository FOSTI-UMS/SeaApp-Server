<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class Product
{
   
   static function getAll($page = 0, $kategori = "all")
   {
      if ($kategori == "all") {
         $page = 20 * $page;
         return DB::table('products')
         ->offset($page)
         ->limit(20)
         ->get(); 
      } else {
         $page = 20 * $page;
         return DB::table('products')
         ->where('kategori_id', $kategori)
         ->offset($page)
         ->limit(20)
         ->get(); 
      }
  
   }

   static function getAllByUserId($page = 0, $kategori = "all", $userid=0)
   {
      if ($kategori == "all") {
         $page = 20 * $page;
         return DB::table('products')
         ->where('vendor', $userid)
         ->offset($page)
         ->limit(20)
         ->get(); 
      } else {
         $page = 20 * $page;
         return DB::table('products')
         ->where('kategori_id', $kategori)
         ->where('vendor', $userid)
         ->offset($page)
         ->limit(20)
         ->get(); 
      }
  
   }

   static function insertOne($product)
   {
      return DB::table('products')->insert($product);
   }

}
