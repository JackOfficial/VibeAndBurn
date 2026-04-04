@extends('admin.layouts.app')
@section('content')

{{-- 1. Added 'search' to x-data --}}
<div class="content-wrapper" x-data="{ showDeleteModal: false, deleteUrl: '', search: '' }">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Social Media Categories</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.category.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus-circle mr-1"></i> Add New Category
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    
                    {{-- 2. Added the Search Input Field --}}
                    <div class="mb-3">
                        <div class="input-group shadow-sm" style="max-width: 400px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" 
                                   class="form-control border-left-0" 
                                   placeholder="Live search categories..." 
                                   x-model="search">
                        </div>
                    </div>

                    {{-- Alerts (Alpine-powered) --}}
                    @if (Session::has('deleteCategorySuccess') || Session::has('updateCategorySuccess') || Session::has('addCategorySuccess'))
                        <div x-data="{ show: true }" 
                             x-show="show" 
                             x-init="setTimeout(() => show = false, 5000)"
                             x-transition.duration.500ms
                             class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ Session::get('deleteCategorySuccess') ?? Session::get('updateCategorySuccess') ?? Session::get('addCategorySuccess') }}
                            <button type="button" class="close" @click="show = false">&times;</button>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0" style="border-radius: 12px;">
                        <div class="card-header bg-white py-3">
                            <h3 class="card-title text-muted font-weight-bold">
                                <i class="fas fa-list mr-2 text-primary"></i> Total Categories: {{ $categoryCounter }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Platform</th>
                                        <th>Category Name</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                    {{-- 3. Added x-show logic to filter rows based on Platform or Category Name --}}
                                    <tr x-show="search === '' || 
                                               '{{ strtolower($category->socialmedia->socialmedia ?? '') }}'.includes(search.toLowerCase()) || 
                                               '{{ strtolower($category->category) }}'.includes(search.toLowerCase())"
                                        x-transition:enter.duration.300ms>
                                        
                                        <td class="align-middle text-muted small">{{ $loop->iteration }}</td>
                                        <td class="align-middle font-weight-bold">
                                            {{ $category->socialmedia->socialmedia ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle text-primary">{{ $category->category }}</td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-outline-success mr-2" 
                                                   href="{{ route('admin.category.edit', $category->id) }}">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        @click="deleteUrl = '{{ route('admin.category.destroy', $category->id) }}'; showDeleteModal = true">
                                                    <i class="fa fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    {{-- 4. No Results Message --}}
                                    <template x-if="search !== '' && $el.closest('tbody').querySelectorAll('tr[style*=\'display: none\']').length === {{ count($categories) }}">
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                No categories found matching "<span x-text="search"></span>"
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Delete Modal (remains same) --}}
    <div x-show="showDeleteModal" ...> </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // NOTE: If you use Alpine Live Search, you should disable 
        // the default DataTables search box to avoid confusion.
        $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
            "searching": false, // Set to false if using Alpine search
        });
    });
</script>
@endsection