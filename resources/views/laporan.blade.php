<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Kabayan - Edit</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md border mt-10">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📊 Laporan Penjualan</h2>
            
            <div class="flex bg-gray-100 p-1 rounded-lg">
                <a href="?type=week" class="px-4 py-2 rounded-md {{ $type == 'week' ? 'bg-white shadow text-blue-600 font-bold' : 'text-gray-500' }}">
                    Mingguan
                </a>
                <a href="?type=month" class="px-4 py-2 rounded-md {{ $type == 'month' ? 'bg-white shadow text-blue-600 font-bold' : 'text-gray-500' }}">
                    Bulanan
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-50 text-blue-800 italic">
                        <th class="p-3 border-b">Periode ({{ ucfirst($type) }})</th>
                        <th class="p-3 border-b text-center">Jumlah Transaksi</th>
                        <th class="p-3 border-b text-center">Total Barang Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 border-b font-medium text-gray-700">{{ $row->label }}</td>
                        <td class="p-3 border-b text-center">{{ $row->total_sales }} Transaksi</td>
                        <td class="p-3 border-b text-center">{{ $row->total_items }} pcs</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-10 text-center text-gray-400 italic">Belum ada data penjualan untuk periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Terjadi Kesalahan!</strong>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>