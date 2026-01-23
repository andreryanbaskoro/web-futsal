@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- FORM PROFILE --}}
    @include('admin.profile._form-profile')

    {{-- FORM GANTI PASSWORD --}}
    @include('admin.profile._form-password')

</div>
@endsection