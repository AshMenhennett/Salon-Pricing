@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <a href="{{ route('products.index') }}" class="pull-left">&laquo; Back to Products</a>
            <br />
            <div class="panel panel-default">
                <div class="panel-heading">Create a Product</div>

                <div class="panel-body">
                    <form action="{{ route('products.create.submit') }}" method="post">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('brand') ? ' has-error' : '' }}">
                                    <label for="brand" class="label-control">Brand</label>
                                    <product-brand-select selected-brand="{{ null }}"></product-brand-select>
                                    @if ($errors->has('brand'))
                                        <div class="help-block danger">
                                            {{ $errors->first('brand') }}
                                        </div>
                                    @endif
                                    <br />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="label-control">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" required>
                                    @if ($errors->has('name'))
                                        <div class="help-block danger">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                    <label for="price" class="label-control">Price</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">$</div>
                                        <input type="text" name="price" class="form-control" id="price" placeholder="Price" required>
                                    </div>
                                    @if ($errors->has('price'))
                                        <div class="help-block danger">
                                            {{ $errors->first('price') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                                    <label for="category" class="label-control">Category</label>
                                    <product-category-select selected-category="{{ null }}"></product-category-select>
                                    @if ($errors->has('category'))
                                        <div class="help-block danger">
                                            {{ $errors->first('category') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-default btn-block btn-form-lower pull-right">Create Product</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
