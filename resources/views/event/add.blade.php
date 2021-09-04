@extends('layouts.app')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="frmEvent" name="frmEvent" action="{{ ($mode == 'add') ? route('event.store') : route('event.update')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                            <input id="id" name="id" type="hidden" class="form-control" value="{{ @$event->id }}">
                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Title</label>
                                <div class="col-sm-8">
                                    <input id="title" type="text" name="title" value="{{ old('title') ?? $event->title }}" class="form-control">
                                    @error('title')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Start Date</label>
                                <div class="col-sm-8">
                                    <input id="start_date" type="text" name="start_date" value="{{ old('start_date') ?? $event->start_date }}" class="form-control">
                                    @error('start_date')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">End Date</label>
                                <div class="col-sm-8">
                                    <input id="end_date" type="text" name="end_date" value="{{ old('end_date') ?? $event->end_date }}" class="form-control">
                                    @error('end_date')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 form-control-label">Recurrence</label>
                                <div class="col-md-8">
                                    <div class="form-check">
                                        <input type="radio" name="recurrence_type" id="recurrence_type_yes" value="repeat" class="form-check-input" @if($event->recurrence_type == 'repeat') checked @endif>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Repeat
                                        </label>
                                        <select name="repeat_type1">
                                            <option value="Every" @if(@$recurrence_at[0] == "Every") selected @endif>Every</option>
                                            <option value="Every Other" @if(@$recurrence_at[0] == "Every Other") selected @endif>Every Other</option>
                                            <option value="Every Third" @if(@$recurrence_at[0] == "Every Third") selected @endif>Every Third</option>
                                            <option value="Every Fourth" @if(@$recurrence_at[0] == "Every Fourth") selected @endif>Every Fourth</option>
                                        </select>
                                        <select name="repeat_type2">
                                            <option value="Day" @if(@$recurrence_at[1] == "Day") selected @endif>Day</option>
                                            <option value="Week" @if(@$recurrence_at[1] == "Week") selected @endif>Week</option>
                                            <option value="Month" @if(@$recurrence_at[1] == "Month") selected @endif>Month</option>
                                            <option value="Year" @if(@$recurrence_at[1] == "Year") selected @endif>Year</option>
                                        </select>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="recurrence_type" id="recurrence_type_No" value="repeat_on" class="form-check-input" @if($event->recurrence_type == 'repeat_on') checked @endif>
                                        <label class="form-check-label" for="exampleRadios2">
                                            Repeat on the
                                        </label>
                                        <select name="repeat_on_type1">
                                            <option value="First" @if(@$recurrence_at[0] == "First") selected @endif>First</option>
                                            <option value="Second" @if(@$recurrence_at[0] == "Second") selected @endif>Second</option>
                                            <option value="Third" @if(@$recurrence_at[0] == "Third") selected @endif>Third</option>
                                            <option value="Fourth" @if(@$recurrence_at[0] == "Fourth") selected @endif>Fourth</option>
                                        </select>
                                        <select name="repeat_on_type2">
                                            <option value="Sunday" @if(@$recurrence_at[1] == "Sunday") selected @endif>Sunday</option>
                                            <option value="Monday" @if(@$recurrence_at[1] == "Monday") selected @endif>Monday</option>
                                            <option value="Tuesday" @if(@$recurrence_at[1] == "Tuesday") selected @endif>Tuesday</option>
                                            <option value="Wednesday" @if(@$recurrence_at[1] == "Wednesday") selected @endif>Wednesday</option>
                                            <option value="Thursday" @if(@$recurrence_at[1] == "Thursday") selected @endif>Thursday</option>
                                            <option value="Friday" @if(@$recurrence_at[1] == "Friday") selected @endif>Friday</option>
                                            <option value="Saturday" @if(@$recurrence_at[1] == "Saturday") selected @endif>Saturday</option>
                                        </select>
                                        <select name="repeat_on_type3">
                                            <option value="Month" @if(@$recurrence_at[2] == "Every") selected @endif>Month</option>
                                            <option value="3 Months" @if(@$recurrence_at[2] == "3 Months") selected @endif>3 Months</option>
                                            <option value="4 Months" @if(@$recurrence_at[2] == "4 Months") selected @endif>4 Months</option>
                                            <option value="6 Months" @if(@$recurrence_at[2] == "Year") selected @endif>Year</option>
                                        </select>
                                    </div>
                                    <span id="recurrence_type-error" class="error"></span>
                                    @error('recurrence_type')
                                    <span class="error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="line"></div>
                            <div class="form-group row">
                                <div class="col-sm-4 offset-sm-3">
                                    <button type="button" class="btn btn-secondary" onclick="javascript:window.location.href='{{ route('event.index') }}';">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </form>
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
