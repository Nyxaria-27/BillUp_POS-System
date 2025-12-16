@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl">
    <div class="mb-4 lg:mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm lg:text-base">
            ← Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-4 lg:p-6">
        <h3 class="text-base lg:text-lg font-semibold text-gray-800 mb-4 lg:mb-6">Tambah Produk Baru</h3>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="col-span-1 lg:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                        class="w-full px-3 lg:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base" 
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <select id="category_id" name="category_id" 
                        class="w-full px-3 lg:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base" 
                        required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="1000"
                        class="w-full px-3 lg:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base" 
                        required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 lg:col-span-2">
                    <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock') }}" min="0"
                        class="w-full px-3 lg:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base" 
                        required>
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 lg:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-3 lg:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 lg:col-span-2">
                    <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                    <input type="file" id="image" name="image" accept="image/*"
                        class="w-full px-3 lg:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm lg:text-base">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Max: 2MB</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 lg:mt-6 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button type="submit" class="px-4 lg:px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition text-sm lg:text-base">
                    Simpan
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-4 lg:px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-center text-sm lg:text-base">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
