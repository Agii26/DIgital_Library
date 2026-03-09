@extends('layouts.app')

@section('page-title', 'Edit Book')

@section('content')
<div class="max-w-3xl">

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-5 font-medium">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Book Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Accession No.</label>
                    <input type="text" value="{{ $book->accession_no }}"
                        class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed"
                        disabled />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type</label>
                    <input type="text" value="{{ ucfirst($book->type) }}"
                        class="w-full border border-gray-100 bg-gray-50 rounded-xl px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed"
                        disabled />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Author <span class="text-red-400">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
                    <input type="text" name="category" value="{{ old('category', $book->category) }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="e.g. Science, Fiction" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₱) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $book->price) }}" step="0.01" min="0"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status <span class="text-red-400">*</span></label>
                    <select name="status"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required>
                        <option value="available" {{ $book->status === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ $book->status === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="reserved" {{ $book->status === 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="damaged" {{ $book->status === 'damaged' ? 'selected' : '' }}>Damaged</option>
                        <option value="lost" {{ $book->status === 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition resize-none"
                        placeholder="Brief description of the book...">{{ old('description', $book->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Cover Image -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Cover Image</h3>
            <div class="flex items-center gap-5">
                <div class="w-20 h-28 bg-gray-100 rounded-xl overflow-hidden shrink-0" id="cover-preview">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs font-semibold">No Image</div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Replace Cover</label>
                    <input type="file" name="cover_image" accept="image/*" id="cover-input"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-400 mt-1.5">Leave blank to keep current image</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <button type="submit"
                class="bg-blue-700 text-white px-7 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-800 active:scale-95 transition-all shadow-sm">
                Update Book
            </button>
            <a href="{{ route('admin.books.index') }}"
                class="bg-gray-100 text-gray-600 px-7 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    document.getElementById('cover-input').addEventListener('change', function() {
        const preview = document.getElementById('cover-preview');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" />`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection