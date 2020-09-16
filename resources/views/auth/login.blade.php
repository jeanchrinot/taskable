@extends('layouts.app')

@section('head-scripts')
    <script type="text/javascript">
        if ((localStorage.getItem('userToken'))) {
            window.location.replace('/app/dashboard');
        }
    </script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <login-form></login-form>
        </div>
    </div>
    @include('includes.flash')
</div>
@endsection
