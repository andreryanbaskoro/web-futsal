@extends('layouts.owner')

@section('content')
<div class="space-y-6">

    {{-- FLASH MESSAGE --}}
    @include('elements.flash-messages')

    {{-- FORM PROFILE --}}
    @include('owner.profile._form-profile')

    {{-- FORM GANTI PASSWORD --}}
    @include('owner.profile._form-password')

</div>
@endsection

