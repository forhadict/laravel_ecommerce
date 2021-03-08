    @extends('frontend.layouts.master')
    
    @section('content')
    
    <!-- Start Sidebar and Content-->
    <div class="container margin-top-20">
        <div class="row">
            <div class="col-md-4">
                @include('frontend.partials.productSidebar')
            </div>
            <div class="col-md-8">
                <div class="widget">
                    <h3>All Products</h3>
                    @include('frontend.pages.product.partials.all_product')
                </div>
            </div>
        </div>
    </div>
    <!-- End Sidebar and Content-->
        
    @endsection