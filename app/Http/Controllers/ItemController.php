<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Member;
use App\Models\Transaction;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_items = Item::all();
        $cart = session()->get('cart', []);
        
        // Hitung total di sini
        $totalSemua = 0;
        foreach ($cart as $details) {
            $totalSemua += ($details['price'] ?? 0) * $details['quantity'];
        }

        // Kirim $totalSemua ke view
        return view('toko', compact('all_items', 'totalSemua'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function createView()
    {
        return view ('tambah');
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:items,name',
            'stock' => 'required|integer|min:0'
        ]);

        Item::create($request->all());

        // Setelah simpan, balik ke halaman utama (toko) dengan pesan sukses
        return redirect()->route('home')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function addToCart(Request $request)
    {
        // 1. Validasi input quantity
        $request->validate([
            'id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $item = Item::findOrFail($request->id);
        $requestedQty = $request->quantity; // Jumlah yang diinput user
        $cart = session()->get('cart', []);
        
        // 2. Hitung jumlah yang sudah ada di keranjang (jika ada)
        $currentQtyInCart = isset($cart[$item->id]) ? $cart[$item->id]['quantity'] : 0;

        // 3. CEK: Apakah total (di keranjang + input baru) melebihi stok?
        if (($currentQtyInCart + $requestedQty) > $item->stock) {
            $sisaBisaDibeli = $item->stock - $currentQtyInCart;
            return redirect()->back()->with('error', "Gagal! Stok tersisa {$item->stock}. Kamu sudah punya {$currentQtyInCart} di keranjang. Maksimal tambah {$sisaBisaDibeli} lagi.");
        }

        // 4. Update atau Tambah ke keranjang
        if(isset($cart[$item->id])) {
            $cart[$item->id]['quantity'] += $requestedQty;
        } else {
            $cart[$item->id] = [
                "name" => $item->name,
                "quantity" => $requestedQty,
                "price" => $item->harga,
                "stock" => $item->stock
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', "{$requestedQty} {$item->name} berhasil masuk keranjang!");
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Keranjang dikosongkan!');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Mengambil semua barang beserta total pembelian per member untuk barang tersebut
        $all_items = Item::all()->map(function($item) {
            // Cari pembeli yang paling banyak membeli barang ini
            $top_buyer = Transaction::where('item_id', $item->id)
                ->with('member')
                ->select('member_id', \DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('member_id')
                ->orderBy('total_qty', 'desc')
                ->first();

            $item->top_buyer_name = $top_buyer ? $top_buyer->member->name : 'Belum ada pembeli';
            $item->top_buyer_qty = $top_buyer ? $top_buyer->total_qty : 0;
            
            return $item;
        });

        return view('edit', compact('all_items'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validasi: items harus berupa array
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string',
            'items.*.harga' => 'required',
            'items.*.stock' => 'required|integer|min:0',
        ]);

        // Lakukan perulangan untuk update tiap baris
        foreach ($request->items as $id => $data) {
            $item = Item::findOrFail($id);
            $item->update([
                'name' => $data['name'],
                'harga' => $data['harga'],
                'stock' => $data['stock'],
            ]);
        }

        return redirect()->route('home')->with('success', 'Seluruh data inventaris berhasil diperbarui!');
    }

    public function check(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        $item = Item::where('name', 'LIKE', '%' . $request->name . '%')->first();

        if (!$item) {
        // Balik ke halaman sebelumnya sambil membawa pesan error
        return redirect()->back()->with('error', '"' . $request->name . ' "Habis');
    }

        // Jika ingin hasil pencarian tampil di halaman 'toko' kembali:
        $all_items = Item::all(); // Ambil semua data lagi untuk tabel
        return view('toko', compact('item', 'all_items'));
    }

    public function checkout(Request $request)
    {
        $request->validate(['member_name' => 'string']);
        $cart = session()->get('cart');

        if (!$cart) return redirect()->back()->with('error', 'Keranjang kosong!');

        // 1. Cari atau buat Member baru berdasarkan nama
        $member = Member::firstOrCreate(['name' => $request->member_name]);

        \DB::transaction(function () use ($cart, $member) {
            foreach ($cart as $id => $details) {
                $item = Item::findOrFail($id);

                // 2. Simpan ke tabel transaksi
                Transaction::create([
                    'member_id' => $member->id,
                    'item_id'   => $item->id,
                    'quantity'  => $details['quantity']
                ]);

                // 3. Potong stok
                $item->decrement('stock', $details['quantity']);
            }
        });

        session()->forget('cart');
        return redirect()->route('home')->with('success', 'Transaksi atas nama ' . $member->name . ' berhasil!');
    }

        public function report(Request $request)
{
    $type = $request->get('type', 'month');

    if ($type == 'week') {
        $data = Transaction::select(
                \DB::raw('DATE(created_at) as label'),
                \DB::raw('SUM(quantity) as total_items'),
                \DB::raw('COUNT(*) as total_sales')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('label')
            ->get()
            ->sortByDesc('label'); // Urutkan pakai Laravel (Collection)
    } else {
        $data = Transaction::select(
                \DB::raw('DATE_FORMAT(created_at, "%Y-%m") as sort_key'), // Tambah key format angka (2026-03)
                \DB::raw('DATE_FORMAT(created_at, "%M %Y") as label'),     // Label buat tampilan (March 2026)
                \DB::raw('SUM(quantity) as total_items'),
                \DB::raw('COUNT(*) as total_sales')
            )
            ->where('created_at', '>=', now()->startOfYear())
            ->groupBy('sort_key', 'label') // Masukkan keduanya ke Group By
            ->orderBy('sort_key', 'desc')  // Urutkan berdasarkan angka tahun-bulan
            ->get();
    }

    return view('laporan', compact('data', 'type'));
}
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
