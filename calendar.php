<?php
session_start();
include("db.php");
include("functions.php");

$user_data = check_login($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Event Calendar in PHP + PostgreSQL</title>
    <!-- FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>
    <!-- Your custom styles -->
    <link rel="stylesheet" href="styles.css" />
    
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
      /* Přidám jen malé úpravy, aby FullCalendar lépe seděl */
      #calendar {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Menu</h2>
    <a href="index.php">Home</a>
    <a href="#">Profile</a>
    <a href="#">Storage</a>
    <a href="#">Hours worked</a>
    <a href="calendar.php">Calendar</a>
    <a href="shift_planner.php">Shift planner</a>
    <a href="#">Messages</a>
    <a href="signup.php">User registration</a>
    <a href="logout.php">Log out</a>
</div>

<div class="main">
    <h3 class="text-center mb-4">Dynamic Event Calendar</h3>
    <div id="calendar"></div>
</div>

<!-- Modal pro přidání události -->
<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="event_form">
          <div class="form-group">
            <label for="event_name">Event name</label>
            <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter event name" required>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label for="event_start_date">Event start date</label>
              <input type="date" name="event_start_date" id="event_start_date" class="form-control" required>
            </div>
            <div class="form-group col">
              <label for="event_start_time">Start time</label>
              <input type="time" name="event_start_time" id="event_start_time" class="form-control">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col">
              <label for="event_end_date">Event end date</label>
              <input type="date" name="event_end_date" id="event_end_date" class="form-control" required>
            </div>
            <div class="form-group col">
              <label for="event_end_time">End time</label>
              <input type="time" name="event_end_time" id="event_end_time" class="form-control">
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        defaultView: 'month',
        timeZone: 'local',
        editable: true,
        selectable: true,
        selectHelper: true,
        displayEventTime: true,
        select: function(start, end) {
            // Naplníme form podle výběru
            $('#event_start_date').val(moment(start).format('YYYY-MM-DD'));
            $('#event_start_time').val(moment(start).format('HH:mm'));
            $('#event_end_date').val(moment(end).format('YYYY-MM-DD'));
            $('#event_end_time').val(moment(end).format('HH:mm'));
            $('#event_entry_modal').modal('show');
        },
        events: function(start, end, timezone, callback) {
            $.ajax({
                url: 'display_event.php',
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        var events = [];
                        $.each(response.data, function(i, item) {
                            events.push({
                                id: item.event_id,
                                title: item.title,
                                start: item.start,
                                end: item.end,
                                color: item.color
                            });
                        });
                        callback(events);
                    } else {
                        alert(response.msg);
                    }
                },
                error: function(xhr, status, error) {
                    alert("Error loading events: " + error);
                }
            });
        },
        eventRender: function(event, element) {
            element.bind('click', function() {
                alert('Event ID: ' + event.id + '\nTitle: ' + event.title);
            });
        }
    });
});

function save_event() {
    var event_name = $("#event_name").val();
    var event_start_date = $("#event_start_date").val();
    var event_start_time = $("#event_start_time").val();
    var event_end_date = $("#event_end_date").val();
    var event_end_time = $("#event_end_time").val();

    if (!event_name || !event_start_date || !event_end_date) {
        alert("Please fill in all required fields.");
        return false;
    }

    // Spojíme datum a čas, pokud čas není vyplněný, použijeme výchozí hodnoty
    var event_start_datetime = event_start_date + ' ' + (event_start_time ? event_start_time : '00:00:00');
    var event_end_datetime = event_end_date + ' ' + (event_end_time ? event_end_time : '23:59:59');

    $.ajax({
        url:"save_event.php",
        type:"POST",
        dataType: 'json',
        data: {
            event_name: event_name,
            event_start_date: event_start_datetime,
            event_end_date: event_end_datetime
        },
        success: function(response) {
            $('#event_entry_modal').modal('hide');
            if(response.status === true) {
                alert(response.msg);
                $('#calendar').fullCalendar('refetchEvents');
                // Reset form po úspěchu
                $('#event_form')[0].reset();
            } else {
                alert(response.msg);
            }
        },
        error: function(xhr, status, error) {
            alert("AJAX error: " + error);
        }
    });
    return false;
}
</script>

</body>
</html>