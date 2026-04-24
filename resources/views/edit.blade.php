<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Kabayan - Edit Stok</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen py-12 px-4">

    <div class="max-w-5xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Manajemen Inventaris</h1>
                <p class="text-slate-500 text-sm mt-1">Perbarui nama, stok, dan harga barang secara massal.</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 hover:text-blue-600 transition-all font-semibold shadow-sm text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-8 flex items-start gap-3 shadow-sm">
                <span class="text-lg">❌</span>
                <div>
                    <strong class="font-bold block">Terjadi Kesalahan!</strong>
                    <ul class="text-xs mt-1 list-disc ml-4 opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <form action="{{ route('item.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="w-full border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="p-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest border-bottom border-slate-100">Barang</th>
                                <th class="p-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest border-bottom border-slate-100 w-32">Stok</th>
                                <th class="p-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest border-bottom border-slate-100 w-44">Harga (Rp)</th>
                                <th class="p-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest border-bottom border-slate-100">Insight Pembeli</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($all_items as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4">
                                    <input type="text" name="items[{{ $item->id }}][name]" value="{{ $item->name }}" 
                                        class="w-full px-3 py-2 bg-slate-50 border-transparent border rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-sm font-medium text-slate-700">
                                </td>
                                <td class="p-4">
                                    <input type="number" name="items[{{ $item->id }}][stock]" value="{{ $item->stock }}" 
                                        class="w-full px-3 py-2 bg-slate-50 border-transparent border rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-sm font-semibold text-slate-700 text-center">
                                </td>
                                <td class="p-4">
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400 text-xs font-bold">Rp</span>
                                        <input type="number" name="items[{{ $item->id }}][harga]" value="{{ $item->harga }}" 
                                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border-transparent border rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all text-sm font-semibold text-slate-700">
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="bg-blue-50 p-2 rounded-lg">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-700 leading-none">{{ $item->top_buyer_name ?? '-' }}</p>
                                            @if($item->top_buyer_qty > 0)
                                                <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-tighter">Terbeli: {{ $item->top_buyer_qty }} pcs</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-slate-50/50 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-blue-600 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Simpan Semua Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>