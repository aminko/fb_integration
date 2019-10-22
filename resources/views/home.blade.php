@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                    <hr>
                    <p>Type url you want to share:</p>
                    
                    <form method="POST" class="form-inline" action="{{ route('fb-post-message') }}">
                        @csrf
                        <div class="form-group mx-sm-3 mb-2">
                            <input type="text" name="link" class="form-control @error('link') is-invalid @enderror" id="link" placeholder="Url">
                            @error('link')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Share</button>
                        

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
