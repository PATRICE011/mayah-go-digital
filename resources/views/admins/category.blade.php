@extends('admins.layout')
@section('content')

<div class="main-wrapper">
    <main class="container section">

        <!-- Filters and Table for Categories List -->
        <div class="containers mt-4">
            <h1>Category Management</h1>
            
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td></td>
                        <td>{{ $category->category_name }}</td>
                        <td>
                            <button type="button" class="btn clr-color2" data-toggle="modal" data-target="#editCategoryModal-{{ $category->id }}">
                                Edit
                            </button>
                        </td>
                        <td>
                            <form action="{{ route('admins.category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn clr-color1">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Button to trigger modal -->
            <button type="button" class="btn clr-color1" data-toggle="modal" data-target="#addCategoryModal">
                Add Category
            </button>
        </div>

        <!-- Modal for Add Category -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admins.insertCategory') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control" id="category_name" name="category_name" placeholder="e.g., Biscuits" required>
                            </div>
                            <button type="submit" class="btn clr-color1">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Edit Category -->
        @foreach ($categories as $category)
        <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admins.category.update', $category->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}" required>
                            </div>
                            <button type="submit" class="btn clr-color1">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </main>
</div>

@endsection
