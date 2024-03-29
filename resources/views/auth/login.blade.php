@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                              
                                    <a class="btn btn-primary" href="{{ route('fb-login') }}">
                                        {{ __('Login with Facebook') }}
                                    </a>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
