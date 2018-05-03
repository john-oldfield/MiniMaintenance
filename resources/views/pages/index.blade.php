@extends('layouts.non-dash')

@section('content')
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>Mini Maintenance</h1>
        <h2>A web application designed to make maintaining your Mini easy</h2>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="#" role="button">Get Started</a>
        </p>
    </div>
</div>
<div class="container">
    <div class="row">

        <div class="col-lg-4 promo-box">
            <h1><i class="fas fa-car"></i></h1>
            <h3>Daily Driver</h3>
            <p>Minis shouldn't just be for sunny weekends! With MiniMaintenance you can keep on top with your maintenance and have your pride and joy for every occassion, whether it be the school run, a trip to the shops or the London to Brighton run.</p>
        </div>
        <div class="col-lg-4 promo-box">
            <h1><i class="fas fa-mobile-alt"></i></h1>
            <h3>Responsive</h3>
            <p>With our responsive web application you can tick off maintenance tasks from your mobile! Handy for if you're stuck in the shed or in the waiting room of your local garage.</p>
        </div>
        <div class="col-lg-4 promo-box">
            <h1><i class="fas fa-wrench"></i></h1>
            <h3>Easy to Use</h3>
            <p>Tracking fuel, logging expenses and keeping up with maintenance is made easy with MiniMaintenance. With lots of graphs and to help you visualise how thirsty your Mini is or how much it's costing you.</p>
        </div>
    </div>
</div>
<footer class="container-fluid">
    <div class="row">
        <div class="col-lg-3 offset-lg-2">
            <h3>Company Bio</h3>
            <p>Mini Maintenance is a web application made by John Oldfield as part of his final year project.</p>
        </div>
        <div class="col-lg-3">
            <h3>Helpful Links</h3>
            <ul>
                <li><a href="/login">Login</a></li>
                <li><a href="/login">Register</a></li>
                <li><a href="/login">Sitemap</a></li>
            </ul>
        </div>
        <div class="col-lg-3">
            <h3>Support</h3>
            <ul>
                <li><a href="/help">FAQ</a></li>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Twitter</a></li>
            </ul>
        </div>
    </div>
</footer>
@endsection