@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <div class="card border-0">
            <div class="card-header tbl-nme-head">
                Dashboard
            </div>
            <div class="card-body">
                <h5 class="card-title">Welcome to your dashboard!</h5>
                <p class="card-text">This is your main dashboard area where you can display important information and quick links.</p>
                <!-- Add more dashboard content here -->
                <div class="dc-cl-stats-grid">
                                <div class="dc-cl-stat-card">
                                    <i class="fas fa-users-cog"></i>
                                    <h3>Total Users</h3> 
                                    <p class="dc-cl-stat-value">1,234</p>
                                </div>
                                <div class="dc-cl-stat-card">
                                    <i class="fas fa-project-diagram"></i>
                                    <h3>Active Projects</h3>
                                    <p class="dc-cl-stat-value">45</p>
                                </div>
                                <div class="dc-cl-stat-card">
                                    <i class="fas fa-chart-line"></i>
                                    <h3>Revenue (Last 30 Days)</h3>
                                    <p class="dc-cl-stat-value">$12,345</p>
                                </div>
                                <div class="dc-cl-stat-card">
                                    <i class="fas fa-tasks"></i>
                                    <h3>Pending Tasks</h3>
                                    <p class="dc-cl-stat-value">7</p>
                                </div>
                            </div>
            </div>
        </div>
    </div>
</div>
@endsection