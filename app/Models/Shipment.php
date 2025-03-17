<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


{
    //
}
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = ['tracking_number', 'recipient_name', 'address', 'shipment_date', 'status'];
}
namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;


{
    
    {
        $shipments = Shipment::all(); // جلب جميع الشحنات
        return view('shipments.index', compact('shipments'));
    }

    {
        return view('shipments.create');
    }

    
    {
        $request->validate([
            'tracking_number' => 'required|unique:shipments',
            'recipient_name' => 'required',
            'address' => 'required',
            'shipment_date' => 'required|date',
        ]);

        // حفظ الشحنة
        Shipment::create($request->all());
        
        // إعادة التوجيه إلى صفحة إدارة الشحنات بعد إضافة الشحنة
        return redirect()->route('shipments.index')->with('success', 'تم إضافة الشحنة بنجاح');
    }

    
    {
        $shipment = Shipment::findOrFail($id);
        return view('shipments.edit', compact('shipment'));
    }

    
    {
        $request->validate([
            'tracking_number' => 'required',
            'recipient_name' => 'required',
            'address' => 'required',
            'shipment_date' => 'required|date',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update($request->all());

        return redirect()->route('shipments.index')->with('success', 'تم تحديث الشحنة بنجاح');
    }

    
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'تم حذف الشحنة بنجاح');
    }
}


