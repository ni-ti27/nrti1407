<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RTI Nic</title>

  <style>
    body, html {
      margin: 0;
      padding: 0;
      width: 100%;
      height: 100%;
    }

    .full-width-bg {
      width: 100%;
      /* background-color: #52a9e8; */
      background: linear-gradient(90deg, rgba(9, 90, 139, 0.5), rgba(103, 183, 229, 0.2), rgba(103, 183, 229, 0.5));

      padding-top: 20px;
    }

    .nav-link {
      color: aliceblue;
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }
  </style>
</head>
<body>

<div class="full-width-bg">
  <div class="container mt-5" style="border:none">
    <!-- Content inside the container will remain centered -->
    <div class="row justify-content-center">
      <nav class="navbar navbar-expand-lg" style="background-color:#52a9e8">
        <div class="container-fluid">
          <span class="navbar-brand mb-0 h1" style="color:aliceblue">{{ Auth::user()->name }} </span>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
              @if( Session::get('role') < 4)
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{new_req}">New Requests</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{in_process}">In Process Requests</a>
                </li>
                <li class="nav-item">
                <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">Closed & Other Requests</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/RTIMIS/dashboard/{closed}">Closed & Other Requests</a>
                    <a class="nav-link" href="/RTIMIS/dashboard/{transForward}">Transfered and Forwarded Requests</a>
                  </div>
                </div>
              </li>
              @endif

              @if( Session::get('role') == 5 || Session::get('role') == 6)
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{new_SA}">New Requests SA</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{in_process_SA}">In Process Requests SA</a>
                </li>
                <li class="nav-item">
                <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">Closed & Other Requests SA</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/RTIMIS/dashboard/{closed_SA}">Closed & Other Requests SA</a>
                  </div>
                </div>
              </li>
              @endif

              @if(Session::get('role') < 4)
              <li class="nav-item">
                <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">Application Status</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/RTIMIS/departmentStatusNR">Department wise actions completed</a>
                    <a class="nav-link" href="/RTIMIS/appStatusNR">Application wise actions completed</a>
                    <a class="nav-link" href="/RTIMIS/statusNR">Received Applications Status</a>
                  </div>
                </div>
              </li>
              @endif

              @if( Session::get('role') == 4  || Session::get('role') == 1)
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{new_FA}">New First Appeal</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{in_process_FA}">In Process First Appeal</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/RTIMIS/dashboard/{closed_FA}">Closed & Other First Appeal</a>
                </li>
              @endif

              @if(Session::get('role') == 4 || Session::get('role') == 1)
              <li class="nav-item">
                <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">FA Application Status</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/RTIMIS/departmentStatusFA">Department wise FA actions completed</a>
                    <a class="nav-link" href="/RTIMIS/appStatusFA">Application wise FA actions completed</a>
                    <a class="nav-link" href="/RTIMIS/statusFA">Received FA Applications Status</a>
                  </div>
                </div>
              </li>
              @endif

              @if( Session::get('role') == 2 || Session::get('role') == 3)
              <li class="nav-item">

              <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">View Query</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/getCommentLists">View / Enter PIO Comment</a>
                    <a class="nav-link" href="/getNotices">View Notices</a>
                  </div>
                </div>

              </li>
              @elseif(Session::get('role') == 4)
              <a class="nav-link" href="/getCommentLists">View PIO Comments</a>
              @endif

                        <li class="nav-item">
                <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">Master & Reports</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/reports">Application Stats</a>

                  </div>
                </div>
              </li>
              <li class="nav-item">
              <div class="dropdown">
                  <a class="nav-link" href="#" data-toggle="dropdown">Profile Update</a>
                  <div class="dropdown-content" id="dropdownOptions">
                    <a class="nav-link" href="/officerDetail">Update Officer Data</a>
                    <a class="nav-link" href="/RTIMIS/updatePass">Change Login Password</a>
                    <!-- <a class="nav-link" href="/UpdatePass">Change Officer Password</a> -->
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
          </form>
        </div>
      </nav>
    </div>
    <div class="row">
      <div class="col-md-5">
        <strong>Search Reg No:</strong>
        <span style="color: blue; font-size: 14px">(Full New Registration Number OR First Appeal Registration Number)</span>
        <form action="{{ route('getRegNo') }}" method="post" id="fetch_reg_no" name="fetch_reg_no" enctype="multipart/form-data">
          @csrf
          <input type="text" class="form-control" name="r_no" id="r_no" placeholder="RSDMBI/NR/0424/00113 OR PWDEPT/FA/0424/00004">
          <button class="btn btn-primary" type="submit">Search</button>
        </form>
      </div>
      <div class="col-md-3 offset-md-1">
        <strong></strong>
      </div>
      <div class="col-md-3">
        <strong>User Manual: </strong>
        <br>
        @if( Session::get('role') == 1)
          <a style="color: blue" href="../Nodal_Module_User_Manual.pdf" target="_blank" download>Nodal User Manual</a>
        @elseif( Session::get('role') == 2 || Session::get('role') == 3)
          <a style="color: blue" href="../PIO_Module_User_Manual.pdf" target="_blank" download>PIO/APIO User Manual</a>
        @elseif( Session::get('role') == 4)
          <a style="color: blue" href="../FA_Dept_User_Manual.pdf" target="_blank" download>FA User Manual</a>
        @elseif( Session::get('role') == 5)
          <a style="color: blue" href="../FA_Dept_User_Manual.pdf" target="_blank" download>Clerk User Manual</a>
        @elseif( Session::get('role') == 6)
          <a style="color: blue" href="../FA_Dept_User_Manual.pdf" target="_blank" download>State Information Commissioner Manual</a>
        @endif
      </div>
    </div>
    <hr>
    <div class="row" style="text-align:center">
      <h6 style="color:red">Nodal / PIO / APIO / First Appellate / Clerk / State Information Commissioner is required to update their Mobile Number and Email Id in "Profile Update" to receive SMS Messages and Notifications.</h6>
    </div>
  </div>
</div>

</body>
</html>
