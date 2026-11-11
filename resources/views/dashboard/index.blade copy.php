@extends('layouts.app')

@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Selamat Datang</strong>
                        </div>
                        <div class="card-body">
                            <h4 class="mb-3">Halo, <strong>{{ $user->name }}</strong>!</h4>
                            <p>Role Anda: <strong>{{ ucfirst($user->role) }}</strong></p>
                            
                            <hr>

                            <h5 class="mb-3">Navigasi Cepat</h5>
                            <ul class="list-unstyled ml-3">
                                <li><a href="{{ route('dashboard') }}"><i class="fa fa-tachometer"></i> Dashboard</a></li>

                                @if ($user->role === 'admin')
                                    <li><a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Manajemen User</a></li>
                                @endif

                                @if (in_array($user->role, ['guru', 'admin']))
                                    <li><a href="/guru"><i class="fa fa-graduation-cap"></i> Halaman Guru</a></li>
                                @endif
                            </ul>

                            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-sign-out"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div><!-- .row -->
        </div><!-- .animated -->
    </div><!-- .content -->
@endsection
