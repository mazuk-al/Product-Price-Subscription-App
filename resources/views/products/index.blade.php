@extends('auth.layouts')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="display-5">
                <div class="title" style="display: inline-block">Products</div>
                <a style="margin: 19px;" href="{{ route('products.create')}}" class="btn btn-primary add-button">+</a>
            </h1>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Title</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Subscribed</td>
                    <td colspan=2>Actions</td>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->title}}</td>
                        <td>{{$product->description}}</td>
                        <td>&#8376;{{$product->price}}</td>
                        <td>{{$product->subscribed}}</td>
                        <td>
                            <a href="{{ route('products.edit',$product->id)}}" class="btn btn-primary">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('products.destroy', $product->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
