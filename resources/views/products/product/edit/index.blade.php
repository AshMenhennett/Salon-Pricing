@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit a Product</div>

                <div class="panel-body">
                    <form action="{{ route('products.product.edit.update', $product) }}" method="post">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('brand') ? ' has-error' : '' }}">
                                    <label for="brand" class="label-control">Brand</label>
                                    <product-brand-select selected-brand="{{ $product->brand }}"></product-brand-select>
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
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') ? old('name') : $product->name }}" required>
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
                                        <input type="text" name="price" class="form-control" id="price" value="{{ number_format((old('price') ? old('price') : $product->price), 2) }}" required>
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
                                    <product-category-select selected-category="{{ $product->category }}"></product-category-select>
                                    @if ($errors->has('category'))
                                        <div class="help-block danger">
                                            {{ $errors->first('category') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-default btn-block btn-form-lower pull-right">Update Product</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
