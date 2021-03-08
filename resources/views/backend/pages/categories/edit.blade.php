@extends('backend.layouts.master')

@section('content')
  <div class="main-panel">
    <div class="content-wrapper">

      <div class="card">
        <div class="card-header">
          Edit Category
        </div>
        <div class="card-body">
          <form action="{{ route('admin.category.update', $category->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('backend.partials.message')
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" id="name" value="{{ $category->name }}">
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Description</label>
              <textarea name="description" rows="8" cols="80" class="form-control">{{ $category->description }}</textarea>

            </div>

            <div class="form-group">
              <label for="parent_id">Parent Category</label>
              <select name="parent_id" id="" class="form-control">
              <option value="">Select Your Category</option>
              @foreach ($main_categories as $main_category)
              <option value="{{ $main_category->id }}"{{ $main_category->id ==$category->parent_id ? 'selected' : '' }}>{{ $main_category->name }}</option>
                
              @endforeach
              </select>
            </div>
             <div class="form-group">
              <label for="image">Category Old Image</label> <br>
                <img src="{{ asset('images/categories/'.$category->image) }}" alt="" width="100px;" height="100px;"><br>
              <label for="image">Category New Image</label> <br>
                <input type="file" class="form-control" name="image" id="image" >
             </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
          </form>
        </div>
      </div>

    </div>
  </div>
  <!-- main-panel ends -->
@endsection
