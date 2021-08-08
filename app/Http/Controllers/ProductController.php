<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;


class ProductController extends ApiController
{


    public function getAll(Request $request)
    {
        $page = $request->query("page","0");
        $kategori = $request->query("category","all");
        return $this->apiResponse(
            $this->success,
            "Berhasil Mendapatkan Data Barang",
            Product::getAll($page, $kategori)
        );
    }

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori' => 'required|integer',
            'nama' => 'required|string',
            'nutrisi' => 'required|string',
            'harga' => 'required|integer',
            'kadaluwarsa' => 'required|string',
            'vendor' => 'required|integer',
            'satuan_harga' => 'required|string',
            'foto1' => 'required|max:5000',
            'foto2' => 'required|max:5000',
            'foto3' => 'required|max:5000',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(
                $this->error,
                "Input data tidak sesuai(Error Validation)",
                $validator->errors()
            );
        }


        try{
            $foto1 = $request->file('foto1');
            $subname1 = time()."_".$foto1->getClientOriginalExtension();
            $foto1->move('./upload/products',$subname1);

            $foto2 = $request->file('foto2');
            $subname2 = time()."_".$foto2->getClientOriginalExtension();
            $foto2->move('./upload/products',$subname2);

            $foto3 = $request->file('foto3');
            $subname3 = time()."_".$foto3->getClientOriginalExtension();
            $foto3->move('./upload/products',$subname3);
        } catch (\Throwable $th) {
            return $this->apiResponse(
                $this->error,
                "ERROR UPLOAD",
                $th
            );
        }

        $newProduct = array(
            'kategori_id' => $request->kategori,
            'nama' => $request->nama,
            'nutrisi' => $request->nutrisi,
            'harga' => $request->harga,
            'kadaluwarsa' => $request->kadaluwarsa,
            'vendor' => $request->vendor,
            'satuan_harga' => $request->satuan_harga,
            'foto1' => $subname1,
            'foto2' => $subname2,
            'foto3' => $subname3,
        );

        Product::insertOne($newProduct);

        return $this->apiResponse(
            $this->success,
            "Berhasil Menambah Barang",
            array()
        );
        
    }

}