@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>New Invoice</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.revenue.invoices.store') }}" method="POST">
        @csrf

        @include('admin.revenue.invoices._form', ['invoice' => null])

        <div class="form-group mt-3">
            <button class="btn btn-primary">Create Invoice</button>
            <a href="{{ route('admin.revenue.invoices.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
