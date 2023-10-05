<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use PhpParser\Node\Name\Relative;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'price' => 'required'
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path(), $imageName);
            return Product::create([
                'title' => $request->input('title'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
                'image' => $imageName,
            ]);
        } else {
            // Handle the case where no image was uploaded
            //return response()->json(['message' => 'No image uploaded'], 400);
            return Product::create([
                'title' => $request->input('title'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        if($request->hasFile('image')){
            $newImage = $request->file('image');
            $imageName = time() . '.' . $newImage->getClientOriginalExtension();

            $newImage->move(public_path(), $imageName);

            if($product->image && File::exists(public_path($product->image))){
                File::delete(public_path($product->image));
            }

            $product->update([
                'title' => $request->input('title'),
                'price' => $request->input('price'),
                'description' => $request->input('description'),
                'image' => $imageName,
            ]);
        } else {
            $product->update($request->only(['title', 'price', 'description']));
        }
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Product::destroy($id);
    }

}

