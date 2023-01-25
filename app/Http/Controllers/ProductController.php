<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;


class ProductController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table("products")->get();
        return view('products',compact('products'));
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	DB::table("products")->delete($id);
    	return response()->json(['success'=>"Product Deleted successfully.", 'tr'=>'tr_'.$id]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        DB::table("products")->whereIn('id',explode(",",$ids))->delete();
        return response()->json(['success'=>"Products Deleted successfully."]);
    }
}