@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/users/dashboard.css') }}">
@endpush
@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-title">Booking Manager</div>
        <ul class="sidebar-nav">
            <li><a href="#"><i class="fas fa-calendar-alt"></i> Events Schedule</a></li>
            <li><a href="#"><i class="fas fa-horse"></i> Horse Registry</a></li>
            <li><a href="#"><i class="fas fa-users"></i> Rider Registry</a></li>
            <!-- More items -->
        </ul>

        <div class="sidebar-title">Shared Documents</div>
        <ul class="sidebar-nav">
            <li><a href="#"><i class="fas fa-users-cog"></i> Team</a></li>
            <li><a href="#"><i class="fas fa-chalkboard-teacher"></i> Lessons</a></li>
            <!-- More items -->
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-row">
            <!-- Calendar Section -->

            
            <div class="card">
                <div class="card-header">March 2022</div>
                <div class="card-body">
                    <div id="calendar"></div> <!-- Calendar will be rendered here -->
                </div>
            </div>

            <!-- Task Manager Section -->
            <div class="card">
                <div class="card-header">Manage Tasks</div>
                <div class="card-body">
                    <ul class="task-list">
                        <li>
                            <span>Complete horse training</span>
                            <span class="task-badge">Today's Task</span>
                        </li>
                        <li>
                            <span>Review lesson plans</span>
                            <span class="task-badge">Today's Task</span>
                        </li>
                        <!-- More tasks -->
                    </ul>
                </div>
            </div>
        </div>

        <div class="content-row">
            <!-- Feedback Section -->
            <div class="card">
                <div class="card-header">Feedback Section</div>
                <div class="card-body">
                    <ul class="feedback-list">
                        <li>Horse market trends</li>
                        <li>Review data together</li>
                    </ul>
                </div>
            </div>

            <!-- Activity Log Section -->
            <div class="card">
                <div class="card-header">Activity Log</div>
                <div class="card-body">
                    <ul class="activity-list">
                        <li>
                            <span>Sketch layout</span>
                            <span class="activity-time">1h 25m 30s</span>
                        </li>
                        <!-- More logs -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include FullCalendar and other scripts if needed -->
<!-- Inclure FullCalendar CSS et JS depuis le CDN -->
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>

<script>
    $(document).ready(function() {
        // Initialiser le calendrier avec FullCalendar
        $('#calendar').fullCalendar({
            locale: 'fr',  // Définit la langue en français
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: true,
            events: [
                {
                    title: 'Événement 1',
                    start: '2024-08-01'
                },
                {
                    title: 'Événement 2',
                    start: '2024-08-07',
                    end: '2024-08-10'
                },
                {
                    title: 'Événement 3',
                    start: '2024-08-09T16:00:00'
                }
                // Ajoutez d'autres événements ici
            ]
        });
    });
</script>
@endsection


