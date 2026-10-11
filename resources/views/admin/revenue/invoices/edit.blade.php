@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit Invoice</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.revenue.invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.revenue.invoices._form', ['invoice' => $invoice])

        <div class="form-group mt-3">
            <button class="btn btn-primary">Update Invoice</button>
            <a href="{{ route('admin.revenue.invoices.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
