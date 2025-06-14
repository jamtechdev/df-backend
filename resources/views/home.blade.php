@extends('layouts.app')

@section('content')
    <style>
       
.dashboard .icon{
  background: #02afff;
  border-radius: 5px;
  font-size: 20px;
  width: 50px;
  height: 50px;
  color: #fff;
     display: flex;
    color: #fff;
    align-items: center;
    justify-content: center;
}

.dashboard .card .fa-chevron-right {
  font-size: 20px;
  color: #000;
}


.dashboard .icon i{
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.dashboard  .card-info{
  border-top: 1px solid #eee;
  margin-top: 20px;
  padding-top: 10px;
}

.dashboard   h3{
  font-size: 20px;
  padding-left: 10px;
}

    </style>

    <div class="container mt-5">
        <!-- <h2 class="mb-4 fw-bold text-center text-dark">Dashboard Overview</h2> -->
        <div class="row g-4 justify-content-center">

            <!-- Total Users -->
            <div class="col-md-6 col-lg-4">
                <div class="card p-3 shadow-sm dashboard">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                        <div class="icon">
                           <i class="fas fa-users"></i>
                        </div>
                        <h3 class="">Total Users</h3>
                        </div>
                        
                    </div>
                    <div class="card-info">
                        <h5 class="fw-bold">{{ $totalUsers }}</h5>
                    </div>
                </div>
            </div>

             <div class="col-md-6 col-lg-4">
                <div class="card p-3 shadow-sm dashboard">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                        <div class="icon">
                           <i class="fas fa-list"></i>
                        </div>
                        <h3 class="">Total Categories</h3>
                        </div>
                        
                    </div>
                    <div class="card-info">
                        <h5 class="fw-bold">{{ $totalCategories }}</h5>
                    </div>
                </div>
            </div>

      <div class="col-md-6 col-lg-4">
                <div class="card p-3 shadow-sm dashboard">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                        <div class="icon">
                           <i class="fas fa-tree"></i>
                        </div>
                        <h3 class="">National Parks</h3>
                        </div>
                        
                    </div>
                    <div class="card-info">
                        <h5 class="fw-bold">{{ $totalNationalParks }}</h5>
                    </div>
                </div>
            </div>


                  <div class="col-md-6 col-lg-4">
                <div class="card p-3 shadow-sm dashboard">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                        <div class="icon">
                           <i class="fas fa-user-tie"></i>
                        </div>
                        <h3 class="">Managers</h3>
                        </div>
                        
                    </div>
                    <div class="card-info">
                        <h5 class="fw-bold">{{ $totalManager }}</h5>
                    </div>
                </div>
            </div>


                  <div class="col-md-6 col-lg-4">
                <div class="card p-3 shadow-sm dashboard">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                        <div class="icon">
                           <i class="fas fa-user-edit"></i>
                        </div>
                        <h3 class="">Content Managers</h3>
                        </div>
                        
                    </div>
                    <div class="card-info">
                        <h5 class="fw-bold">{{ $totalContentManager }}</h5>
                    </div>
                </div>
            </div>


                  <div class="col-md-6 col-lg-4">
                <div class="card p-3 shadow-sm dashboard">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex align-items-center">
                        <div class="icon">
                           <i class="fas fa-user"></i>
                        </div>
                        <h3 class="">Readers</h3>
                        </div>
                        
                    </div>
                    <div class="card-info">
                        <h5 class="fw-bold">{{ $totalReader }}</h5>
                    </div>
                </div>
            </div>
            <!-- 
            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card bg-gradient-primary shadow text-center">
                    <div class="card-body">
                        <i class="fas fa-users dashboard-icon"></i>
                        <h5>Total Users</h5>
                        <h2 class="fw-bold">{{ $totalUsers }}</h2>
                    </div>
                </div>
            </div>

           
            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card bg-gradient-info shadow text-center">
                    <div class="card-body">
                        <i class="fas fa-list dashboard-icon"></i>
                        <h5>Total Categories</h5>
                        <h2 class="fw-bold">{{ $totalCategories }}</h2>
                    </div>
                </div>
            </div>

          
            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card bg-gradient-success shadow text-center">
                    <div class="card-body">
                        <i class="fas fa-tree dashboard-icon"></i>
                        <h5>National Parks</h5>
                        <h2 class="fw-bold">{{ $totalNationalParks }}</h2>
                    </div>
                </div>
            </div>


            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card bg-gradient-success shadow text-center">
                    <div class="card-body">
                        <i class="fas fa-user-tie dashboard-icon"></i>
                        <h5>Managers</h5>
                        <h2 class="fw-bold">{{ $totalManager }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card bg-gradient-success shadow text-center">
                    <div class="card-body">
                        <i class="fas fa-user-edit dashboard-icon"></i>
                        <h5>Content Managers</h5>
                        <h2 class="fw-bold">{{ $totalContentManager }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card dashboard-card bg-gradient-success shadow text-center">
                    <div class="card-body">
                        <i class="fas fa-user dashboard-icon"></i>
                        <h5>Readers</h5>
                        <h2 class="fw-bold">{{ $totalReader }}</h2>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
@endsection