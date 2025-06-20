@extends('layouts.app')

@section('content')
<style>
    .dashboard .card {
        border: 1px solid transparent;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 1rem;
        padding: 1.5rem;
        position: relative;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
        overflow: hidden;
    }

    .dashboard .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .dashboard .icon {
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
        color: #fff;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        margin-right: 15px;
    }

    .dashboard h3 {
        font-size: 1.1rem;
        margin: 0;
        font-weight: 600;
        color: var(--bs-gray-800);
    }

    .dashboard .card-info {
        border-top: 1px solid var(--bs-gray-300);
        margin-top: 20px;
        padding-top: 10px;
    }

    .dashboard .card-info h5 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--bs-primary);
    }

    .dashboard .background-circle {
        position: absolute;
        bottom: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--bs-primary), var(--bs-info));
        opacity: 0.05;
        border-radius: 50%;
        z-index: 0;
    }

    .dashboard .d-flex {
        position: relative;
        z-index: 1;
    }

    .no-access {
        text-align: center;
        margin-top: 50px;
        color: var(--bs-gray-600);
        font-size: 1.2rem;
        font-weight: 500;
    }
</style>

<div class="container-fluid mt-5">
    <div class="row g-4 justify-content-center dashboard">
        @php
            $user = auth()->user();
            $isAdmin = $user->hasRole('admin');
            $assignedProjects = $user->projects()->count();
        @endphp

        @if ($isAdmin || $assignedProjects > 0)
            @php
                $cards = [
                    ['icon' => 'fas fa-users', 'label' => 'Total Users', 'count' => $totalUsers],
                    ['icon' => 'fas fa-list', 'label' => 'Total Categories', 'count' => $totalCategories],
                    ['icon' => 'fas fa-tree', 'label' => 'National Parks', 'count' => $totalNationalParks],
                    ['icon' => 'fas fa-user-tie', 'label' => 'Managers', 'count' => $totalManager],
                    ['icon' => 'fas fa-user-edit', 'label' => 'Content Managers', 'count' => $totalContentManager],
                    ['icon' => 'fas fa-user', 'label' => 'Readers', 'count' => $totalReader],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-md-6 col-lg-4">
                    <div class="card p-4 shadow-sm position-relative">
                        <div class="background-circle"></div>
                        <div class="d-flex align-items-center">
                            <div class="icon">
                                <i class="{{ $card['icon'] }}"></i>
                            </div>
                            <h3>{{ $card['label'] }}</h3>
                        </div>
                        <div class="card-info">
                            <h5 class="fw-bold">{{ $card['count'] }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach

        @else
            <div class="col-12">
                <div class="no-access">
                    <i class="fas fa-lock fa-3x mb-3 text-primary"></i>
                    <p>Your account access will be enabled soon. Please contact your administrator if this message persists.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
