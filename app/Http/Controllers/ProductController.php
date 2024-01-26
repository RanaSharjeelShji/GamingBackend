<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use CommonTrait;

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:250',
                'price' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png',
                // 'user_id' => 'required|exists:users,id'
            ]);

            $image = request()->file('image')->store('product','public');
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'image' => $image,
                // 'user_id' => $request->user_id,
                'user_id' => auth()->user()->id,
            ]);
            return $this->sendSuccess("Add created successfully.", $product);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                if (Storage::exists('/' . $product['image'])) {
                    unlink(storage_path('app/' . $product['image']));
                }
                $product->delete();

                return $this->sendSuccess("Product deleted successfully.", true);
            }
            return $this->sendError("product not found", null);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }

    public function index()
    {
        try {
            $product = Product::with('users')->get();
            return $this->sendSuccess("Product fetched successfully.", $product);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }

    public function show()
    {
        try {
            $user_id = auth()->user()->id;
            $product = User::with('products')->where('id',$user_id)->first();
            return $this->sendSuccess("Product fetched successfully.", $product);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), null);
        }
    }


}
