@extends('layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('event.add') }}"  class="btn btn-primary mb-4 admin-top">Add New</a>
                <div class="card">
                    <div class="card-body">
                        <table class="table text-center" id="data-table">
                            <thead>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Dates</th>
                                <th>Occurence</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('js')
<script src="{{ asset('js/pages/event.js')}}"></script>
@endsection
