@extends('layouts.app')

@section('page-title', 'Add New Book')

@section('content')
<div class="max-w-3xl">

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-5 font-medium">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Book Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Accession No. <span class="text-red-400">*</span></label>
                    <input type="text" name="accession_no" value="{{ old('accession_no') }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="e.g. ACC-001" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Type <span class="text-red-400">*</span></label>
                    <select name="type" id="book-type"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required>
                        <option value="">Select type...</option>
                        <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>Physical</option>
                        <option value="digital" {{ old('type') === 'digital' ? 'selected' : '' }}>Digital</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title <span class="text-red-400">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="Book title" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Author <span class="text-red-400">*</span></label>
                    <input type="text" name="author" value="{{ old('author') }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="Author name" required />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category</label>
                    <input type="text" name="category" value="{{ old('category') }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        placeholder="e.g. Science, Fiction" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (₱) <span class="text-red-400">*</span></label>
                    <input type="number" name="price" value="{{ old('price', 0) }}" step="0.01" min="0"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        required />
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition resize-none"
                        placeholder="Brief description of the book...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Cover Image -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5">Cover Image</h3>
            <div class="flex items-center gap-5">
                <div id="cover-preview" class="w-20 h-28 bg-gray-100 rounded-xl flex items-center justify-center text-gray-300 text-xs font-semibold shrink-0 overflow-hidden">
                    No Image
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload Cover</label>
                    <input type="file" name="cover_image" accept="image/*" id="cover-input"
                        class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <p class="text-xs text-gray-400 mt-1.5">JPG, PNG up to 2MB</p>
                </div>
            </div>
        </div>

        <!-- Digital Book Fields -->
        <div id="digital-fields" class="hidden bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-4">
            <h3 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-5">Digital Book File</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">PDF File <span class="text-red-400">*</span></label>
                <input type="file" name="file_path" accept=".pdf"
                    class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                <p class="text-xs text-gray-400 mt-1.5">PDF files only, up to 50MB</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3">
            <button type="submit"
                class="bg-blue-700 text-white px-7 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-800 active:scale-95 transition-all shadow-sm">
                Add Book
            </button>
            <a href="{{ route('admin.books.index') }}"
                class="bg-gray-100 text-gray-600 px-7 py-2.5 rounded-xl font-semibold text-sm hover:bg-gray-200 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Show/hide digital fields
    document.getElementById('book-type').addEventListener('change', function() {
        document.getElementById('digital-fields').classList.toggle('hidden', this.value !== 'digital');
    });

    // Cover image preview
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