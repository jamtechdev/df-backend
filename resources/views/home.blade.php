@extends('layouts.app')

@section('content')
<style>
    .dashboard-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 1rem;
        cursor: pointer;
        height: 100%;
        /* Force same height */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
    }

    .dashboard-card .card-body {
        padding: 2rem;
        color: #4762f8 !important;
        /* Text Color */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .dashboard-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #4762f8 !important;
        /* Icon Color */
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4 fw-bold text-center text-dark">Dashboard Overview</h2>
    <div class="row g-4 justify-content-center">

        <!-- Total Users -->
        <div class="col-md-4 col-sm-6">
            <div class="card dashboard-card bg-gradient-primary shadow text-center">
                <div class="card-body">
                    <i class="fas fa-users dashboard-icon"></i>
                    <h5>Total Users</h5>
                    <h2 class="fw-bold">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="col-md-4 col-sm-6">
            <div class="card dashboard-card bg-gradient-info shadow text-center">
                <div class="card-body">
                    <i class="fas fa-list dashboard-icon"></i>
                    <h5>Total Categories</h5>
                    <h2 class="fw-bold">{{ $totalCategories }}</h2>
                </div>
            </div>
        </div>

        <!-- Total National Parks -->
        <div class="col-md-4 col-sm-6">
            <div class="card dashboard-card bg-gradient-success shadow text-center">
                <div class="card-body">
                    <i class="fas fa-tree dashboard-icon"></i>
                    <h5>National Parks</h5>
                    <h2 class="fw-bold">{{ $totalNationalParks }}</h2>
                </div>
            </div>
        </div>


        <div class="col-md-4 col-sm-6">
            <div class="card dashboard-card bg-gradient-success shadow text-center">
                <div class="card-body">
                    <i class="fas fa-user-tie dashboard-icon"></i>
                    <h5>Managers</h5>
                    <h2 class="fw-bold">{{ $totalManager }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card dashboard-card bg-gradient-success shadow text-center">
                <div class="card-body">
                    <i class="fas fa-user-edit dashboard-icon"></i>
                    <h5>Content Managers</h5>
                    <h2 class="fw-bold">{{ $totalContentManager }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card dashboard-card bg-gradient-success shadow text-center">
                <div class="card-body">
                    <i class="fas fa-user dashboard-icon"></i>
                    <h5>Readers</h5>
                    <h2 class="fw-bold">{{ $totalReader }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection