<?php

namespace App\Http\Controllers;
use App\Models\Shipment;  
use App\Models\Product;

use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with('product')->get();
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $products = Product::all();
       
        return view('shipments.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_number' => 'required|unique:shipments',
            'shipment_date' => 'required|date',
            'status' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            
        ]);

        $shipment = Shipment::create($validated);

        // تحديث الكمية في المخزون
        $product = Product::find($request->product_id);
        $product->quantity -= $request->quantity;
        $product->save();

        return redirect()->route('shipments.index');
    }

    public function show($id)
    {
        $shipment = Shipment::findOrFail($id);
        return view('shipments.show', compact('shipment'));
    }
}


