@extends('layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12  profile-box profile-res">
                <div class="form-panel profile-content view-border">
                    <div class="form-group row no-pad">
                        <div class="col-sm-6 col-md-4 tb-space">
                            <label for="email" class="col-form-label text-md-right">{{ __('Title') }}</label>
                        </div>
                        <div class="col-sm-6 col-md-8 tb-space">
                            <label>{{ $event->title }}</label>
                        </div>
                    </div>
                    <div class="line"></div>
                    @if($event->events()->count() > 0)
                    @foreach($event->events as $events)
                    <p>{{ $events->date }} - {{ date("l",strtotime($events->date)) }}</p>
                    @endforeach
                    @endif
                    <div class="line"></div>

                    <div class="form-group row no-pad">
                        <div class="col-sm-6 col-md-4 tb-space">
                            <button type="button" class="btn btn-primary" onclick="location.href='{{ route('event.index') }}';"> Back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
