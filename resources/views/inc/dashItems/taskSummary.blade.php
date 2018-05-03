<div class="card dash-card bg-light">
    <div class="card-header">Task Summary</div>
    <ul class="list-group list-group-flush">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        Overdue Maintenance
      <span class="badge badge-primary badge-pill badge-pill-error">{{App\UserTask::countOverdue()}}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        Upcoming Maintenance
      <span class="badge badge-primary badge-pill badge-pill-warning">{{App\UserTask::countUpcoming()}}</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        Completed Maintenance
      <span class="badge badge-primary badge-pill">{{count(App\UserTask::all()->where('car_id', '=', $car->id)->where('completed', '>=', 1))}}</span>
      </li>
    </ul>
</div>
