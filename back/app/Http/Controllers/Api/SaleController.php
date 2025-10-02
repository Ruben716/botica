<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; // 👈 Importar DomPDF

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'items.product'])->get();
        return response()->json($sales, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items'     => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $total = 0;

            // Crear venta
            $sale = Sale::create([
                'client_id' => $request->client_id,
                'total'     => 0, // se actualiza después
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    abort(400, "Stock insuficiente para {$product->name}");
                }

                // Descontar stock
                $product->decrement('stock', $item['quantity']);

                $price = $product->price;
                $subtotal = $price * $item['quantity'];
                $total += $subtotal;

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $price,
                    'subtotal'   => $subtotal,
                ]);
            }

            // Actualizar total
            $sale->update(['total' => $total]);

            return response()->json($sale->load(['client', 'items.product']), 201);
        });
    }

    public function show($id)
    {
        $sale = Sale::with(['client', 'items.product'])->findOrFail($id);
        return response()->json($sale, 200);
    }

    public function destroy($id)
    {
        $sale = Sale::with('items.product')->findOrFail($id);

        DB::transaction(function () use ($sale) {
            // Reponer stock de productos
            foreach ($sale->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $sale->items()->delete();
            $sale->delete();
        });

        return response()->json(['message' => 'Venta eliminada y stock restaurado'], 200);
    }

    // 🔹 Nueva función: Imprimir PDF de la venta
    public function print($id)
    {
        $sale = Sale::with(['client', 'items.product'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.sale', compact('sale'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download("venta_{$sale->id}.pdf");
    }
}
