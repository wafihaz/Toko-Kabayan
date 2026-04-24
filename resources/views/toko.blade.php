<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Stok Toko</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen pb-20">

    <nav class="max-w-2xl mx-auto pt-10 px-4">
        <div class="flex flex-wrap justify-center gap-3 mb-8">
            <a href="{{ route('item.create') }}" class="flex-1 text-center bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl hover:bg-slate-50 transition shadow-sm text-sm font-semibold">
                + Barang Baru
            </a>
            <a href="{{ route('item.edit') }}" class="flex-1 text-center bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl hover:bg-slate-50 transition shadow-sm text-sm font-semibold">
                📝 Edit Stok
            </a>
            <a href="{{ route('item.laporan') }}" class="flex-1 text-center bg-white border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl hover:bg-slate-50 transition shadow-sm text-sm font-semibold">
                📊 Laporan
            </a>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full text-center bg-red-50 border border-red-100 text-red-600 px-4 py-2.5 rounded-xl hover:bg-red-100 transition shadow-sm text-sm font-bold">
                    🚪 Keluar
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 overflow-hidden border border-slate-100">
            
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-8 text-center">
                <h1 class="text-3xl font-bold text-white tracking-tight">Cek Stok Barang</h1>
                <p class="text-blue-100 mt-1 text-sm">Kelola inventaris toko dengan mudah</p>
            </div>

            <div class="p-8">
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                        <span class="text-xl">⚠️</span>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('item.check') }}" method="GET" class="mb-8">
                    <div class="relative group">
                        <input type="text" name="name" placeholder="Cari nama barang..." 
                            class="w-full pl-5 pr-24 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all text-slate-700 placeholder:text-slate-400"
                            value="{{ request('name') }}" required>
                        <button type="submit" class="absolute right-2 top-2 bottom-2 bg-blue-600 text-white px-6 rounded-xl hover:bg-blue-700 transition-all font-semibold shadow-lg shadow-blue-200">
                            Cari
                        </button>
                    </div>
                </form>

                @if(isset($item))
                    <div class="mb-10 p-6 rounded-2xl bg-gradient-to-r from-slate-50 to-white border border-slate-100 shadow-sm transition-all hover:shadow-md">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-xl text-slate-800">{{ $item->name }}</h3>
                                <p class="text-sm text-slate-500 mt-1">Stok tersedia: 
                                    <span class="font-bold {{ $item->stock > 0 ? 'text-blue-600' : 'text-red-500' }}">{{ $item->stock }} pcs</span>
                                </p>
                            </div>
                        </div>
                        
                        @if($item->stock > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-6 flex items-end gap-3">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <div class="flex-1">
                                    <label class="text-[10px] uppercase tracking-wider font-bold text-slate-400 ml-1">Jumlah Beli</label>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $item->stock }}" 
                                        class="w-full mt-1 px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-semibold">
                                </div>
                                <button type="submit" class="bg-slate-900 text-white px-6 py-3 rounded-xl hover:bg-black transition-all font-bold flex items-center gap-2">
                                    <span>+</span> Keranjang
                                </button>
                            </form>
                        @else
                            <div class="mt-4 py-3 px-4 bg-red-50 rounded-xl text-red-600 text-sm font-medium italic text-center">
                                Stok barang sedang kosong.
                            </div>
                        @endif
                    </div>
                @endif

                <div class="space-y-6">
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span>🛒</span> Keranjang
                            </h3>
                            <a href="{{ route('cart.clear') }}" class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1 rounded-full transition">Kosongkan</a>
                        </div>

                        @if(session('cart'))
                            <div class="space-y-3">
                                @foreach(session('cart') as $id => $details)
                                    <div class="flex justify-between items-center bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
                                        <div>
                                            <p class="font-semibold text-slate-700">{{ $details['name'] }}</p>
                                            <p class="text-xs text-slate-400">{{ $details['quantity'] }} unit</p>
                                        </div>
                                        @if($details['quantity'] >= $details['stock'])
                                            <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-1 rounded-md font-bold">STOK LIMIT</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-slate-400 text-sm">Belum ada barang di keranjang.</p>
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('cart.checkout') }}" method="POST" class="bg-white rounded-2xl p-6 border-2 border-dashed border-slate-200">
                        @csrf
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Identitas Pembeli</label>
                            <input type="text" name="member_name" placeholder="Nama pelanggan atau ID member..." 
                                class="w-full bg-slate-50 border-none rounded-xl p-4 focus:ring-2 focus:ring-orange-500 transition-all" required>
                        </div>
                        
                        <button type="submit" class="w-full bg-orange-500 text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-200 hover:bg-orange-600 hover:-translate-y-0.5 transition-all active:scale-95">
                            Konfirmasi & Proses Pembelian
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

</body>
</html>