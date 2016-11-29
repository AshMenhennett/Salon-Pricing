@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edit a Service</div>

                <div class="panel-body">
                    <form action="{{ route('services.service.edit.update', $service) }}" method="post">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label for="title" class="label-control">Title</label>
                                    <input type="text" id="title" name="title" class="form-control" value="{{ $service->title }}" required>
                                    @if ($errors->has('title'))
                                        <div class="help-block danger">
                                            {{ $errors->first('title') }}
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
                                        <input type="text" name="price" class="form-control" id="price" value="{{ $service->price }}" required>
                                    </div>
                                    @if ($errors->has('price'))
                                        <div class="help-block danger">
                                            {{ $errors->first('price') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-default btn-block btn-form-lower pull-right">Update Service</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
