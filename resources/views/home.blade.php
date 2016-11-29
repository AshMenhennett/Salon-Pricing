@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <p><strong>Hey {{ Auth::user()->name }}!</strong> Let's get started!</p>
                    <br />
                    <p>To get things cook'n, let's add some products and services to your account: <a href="{{ route('services.create.index') }}">Add Services</a> and <a href="{{ route('products.create.index') }}">Add Products</a>.</p>
                    <br />
                    <p>Once you have added all your services and products, you can view the pricing lists at anytime, using the top navigation bar!</p>
                    <br />
                    <p>Run down of the top navigation links:</p>
                    <ul>
                        <li>All Prices: a combined pricing list of all of your services and products, ready to go!</li>
                        <li>Service Pricing: a pricing list of all of your services!</li>
                        <li>Product Pricing: a pricing list of all of your products!</li>
                    </ul>
                    <br />
                    <p><em>If you are on your mobile or tablet and can't see the top navigation links, just click the button with the bars on it, located at the top right of the website. Furthermore, you can access your account links, such as creating or editing your services and products at your will!</em></p>
                    <br />
                    <p><strong>Enjoy!</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
