<?php

namespace App\Http\Controllers;
use DB;
use App\Product;
use Illuminate\Http\Request;
use Redirect;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['products'] = Product::orderBy('id','desc')->paginate(10);
  
        return view('product.list',$data);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product.create');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'product_code' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'description' => 'required',
        ]);

        if ($files = $request->file('image')) {
           $destinationPath = 'public/image/'; // upload path
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
           $insert['image'] = "$profileImage";
        }
        
        $insert['title'] = $request->get('title');
        $insert['price'] = $request->get('price');
        $insert['product_code'] = $request->get('product_code');
        $insert['description'] = $request->get('description');
        $insert['created_at'] = date("Y-m-d");

        Product::insert($insert);
   
        return Redirect::to('products')
       ->with('success','Greate! Product created successfully.');
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $where = array('id' => $id);
        $data['product_info'] = Product::where($where)->first();

        return view('product.edit', $data);
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'product_code' => 'required',
            'description' => 'required',
        ]);
        
        $update = ['title' => $request->title, 'description' => $request->description];

        if ($files = $request->file('image')) {
           $destinationPath = 'public/image/'; // upload path
           $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
           $files->move($destinationPath, $profileImage);
           $update['image'] = "$profileImage";
        }
        $update['price'] = $request->get('price');
        $update['title'] = $request->get('title');
        $update['product_code'] = $request->get('product_code');
        $update['description'] = $request->get('description');

        Product::where('id',$id)->update($update);
  
        return Redirect::to('products')
       ->with('success','Great! Product updated successfully');
    }
  
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::where('id',$id)->delete();
  
        return Redirect::to('products')->with('success','Product deleted successfully');
    }
    
        public function show()
        {
            
            $product=DB::select('select * from products');
            return view('menu',['product'=> $product]);
        }
    
}
