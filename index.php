<?php require 'functions.php'; ?>
<html>
<head>
    <meta charset="utf-8">
    <title>$title</title>
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">

    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/js/jquery-3.1.0.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("userschedule").innerHTML = this.responseText;
    }
  };
  xhttp.open("POST", "scheduleread.php", true);
  xhttp.send();

  //schedule search function
  function searchschedule(val) {
    var filter, ul, li , span, i, txtValue;
    filter = val.toUpperCase();
    ul = document.getElementById("userschedule");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        span = li[i].getElementsByTagName("span")[0];
        txtValue = span.textContent || span.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
  }

  //schedule remove function
  function scheduleremove(id, name) {
    if(confirm("do you want to remove a schedule:"+name+"?")){
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("sche_"+name).remove();
            alert(this.responseText);
          }
      };
      xhttp.open("GET","scheduleremove.php?id="+id,true);
      xhttp.send();
  }
    else{
      return;
    }
  }
  function selectschedule(id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        res = JSON.parse(this.responseText);
        results = res[0];
        $("#name").val(results['name']);
        $("#email").val(results['email']);
        $("#timefrom").val(results['timefrom']);
        $("#timeto").val(results['timeto']);
        $("#callfrom").val(results['callfrom']);
        $("#callto").val(results['callto']);
        $("#callduration").val(results['callduration']);
        $("#talkduration").val(results['talkduration']);
        $("#status").val(results['status']);
        $("#drunk").val(results['drunk']);
        $("#communicationtype").val(results['communicationtype']);
        $("#pincode").val(results['pincode']);
        $("#number_fuzzy").prop( "checked", parseInt(results['number_fuzzy'])?true:false );
        searchtable();
      }
    };
    xhttp.open("GET", "selectschedule.php?id="+id, true);
    xhttp.send();
  }
  //save schedule
  function saveschedule() {
    if($('#email').val()==""){alert("the 'receiver email' item should be provided!");return;}
    if($('#name').val()==""){alert("the 'Report name' item should be provided!");return;}
    var data = {
      email:$("#email").val(),
      name:$("#name").val(),
      timefrom:$("#timefrom").val(),
      timeto:$("#timeto").val(),
      callfrom:$("#callfrom").val(),
      callto:$("#callto").val(),
      callduration:$("#callduration").val(),
      talkduration:$("#talkduration").val(),
      status:$("#status").val(),
      drunk:$("#drunk").val(),
      communicationtype:$("#communicationtype").val(),
      pincode:$("#pincode").val(),
      number_fuzzy:$("#number_fuzzy:checked").val()?1:0,
    }
    $.ajax({
        type: "POST",
        // dataType: "json",
        url: "./saveschedule.php",
        data: data,
        // contentType: "application/json; charset=utf-8",
        success: function(data){
            alert(data);
            window.location.href=window.location.pathname;
        },
        error: function(e){
            console.log(e.message);
        }
    })

  }
  function newcreate() {
    $("#email").val("");
    $("#name").val("");
    $("#timefrom").val("Day");
    $("#timeto").val("");
    $("#callfrom").val("");
    $("#callto").val("");
    $("#callduration").val("");
    $("#talkduration").val("");
    $("#status").val("All");
    $("#drunk").val("All");
    $("#communicationtype").val("All");
    $("#pincode").val("");
    readtable()
  }
  // let timerId = function tick() {
  //   alert('tick');
  //   timerId = setTimeout(tick, 5000); // (*)
  // }, 2000);

  // setTimeout(sendmail, 1000);
  var mymail = setTimeout(function sendmail() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
      }
    };
    xhttp.open("POST", "sendmail.php", true);
    xhttp.send();
    mymail = setTimeout(sendmail, 300000)
  }, 2000);
</script>
</head>
<body>
<div class="content">
  <div class="col-md-3 border-full">
    <h3>Yeastar Report scheduler</h3><button class="btn btn-primary newcreatebtn" onclick="newcreate()">create new report</button>
    <h4></h4>
    <input type="text" id="searchschedule" class="form-control" onchange="searchschedule(this.value)" placeholder="Search...">
    <div style="width: 100%;height: 84%;overflow: auto;">
      <ul class="list-group" id="userschedule">
          <li class="list-group-item">no schedule</li>
      </ul>
    </div>
  </div>
  <div class="col-md-9 border-full">
    <div class="col-md-12">
      <div class="row">
      <form class="form-horizontal">
        <div class="col-md-10">
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Report Name:</div>
              <div class="col-sm-7">
                <input type="text" class="form-control" name="name" id="name" placeholder="Name...">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Receiver Email:</div>
              <div class="col-sm-7">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email...">
              </div>
            </div>
          </div>

        </div>
      </form>
      </div>
    </div>
    <div class="row">
    <!-- save sending message via email -->
      <div class="col-md-12">
    <h4></h4>
      <div class="col-md-10">
        <form class="form-horizontal">
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Date:</div>
              <div class="col-sm-7">
                <select id="timefrom" name="timefrom" class="form-control" ><option value="Day">Daily</option><option value="Week">Weekly</option><option value="Month">Monthly</option></select> 
              </div>
            </div>
        </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Time:</div>
              <div class="col-sm-7">
                <input type="time" id="timeto" name="timeto" class="form-control" value=<?php echo date('H:i:s');?> >
              </div>
            </div>
        </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Call From:</div>
              <div class="col-sm-7">
                <input type="text" id="callfrom" name="callfrom" class="form-control">
              </div>
            </div>
        </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Call To:</div>
              <div class="col-sm-7">
                <input type="text" id="callto" name="callto" class="form-control" >
              </div>
            </div>
        </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-7">Call Duration more than(s):</div>
              <div class="col-sm-5">
                <input type="number" id="callduration" name="callduration" class="form-control" >
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-7">Talk Duration more than(s):</div>
              <div class="col-sm-5">
                <input type="number" id="talkduration" name="talkduration" class="form-control" >
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <div class="col-sm-5">Status:</div>
              <div class="col-sm-7">
                <select class="form-control" id="status" name="status"><option>All</option><option>Answered</option><option>No Answer</option><option>Busy</option><option>Failed</option><option>Voicemail</option></select>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <h4><a href="#demo" data-toggle="collapse"><i class="fa fa-angle-down">Advanced Options</i></a></h4>
          <div id="demo" class="collapse">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-sm-5">Rrunk:</div>
                  <div class="col-sm-7">
                    <select class="form-control" id="drunk" name="drunk"><option>All</option><option>DIGIT1 (E1)</option><option>GSM2-1(GSM)</option><option>FXO2-3(FXO)</option><option>FXO2-4(FXO)</option><option>BRI2-5(BRI)</option><option>BRI2-6(BRI)</option></select>
                  </div>
                </div>
            </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-sm-5">Communication Type:</div>
                  <div class="col-sm-7">
                    <select class="form-control" id="communicationtype" name="communicationtype"><option>All</option><option>Inbound</option><option>Outbount</option><option>Internal</option><option>Multisite Interconnect</option><option>Callback</option><option>Transfer</option><option>Warning</option></select>
                  </div>
                </div>
            </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-sm-5">PIN Code:</div>
                  <div class="col-sm-7">
                    <input type="number" class="form-control"  id="pincode" name="pincode">
                  </div>
                </div>
            </div>
              <div class="col-md-6">
                <div class="form-group">
                  <div class="col-sm-7"><label class="checkbox-inline"><input type="checkbox" class="form-check-input" id="number_fuzzy" name="number_fuzzy">Number Fuzzy Search<span class="badge">!</span></label></div>
                  <div class="col-sm-5">
                  </div>
                </div>
            </div>
            </div>
          </div>
          <div style="margin-left: 60%;">
            <button class="btn btn-info" type="button" onclick="saveschedule()">Save Schedule</button>
            <button class="btn btn-info" type="button" onclick="searchtable()">Search for current day</button>
          </div>
        </div>
         </form>
      </div>
      <div class="col-md-2"></div>
      </div>
    </div>
    <div class="col-md-12">
      <div>
       <table class="table" style="display:block">
        <thead>
          <tr>
            <th style="width: 5%">No</th>
            <th style="width: 10%">Time</th>
            <th style="width: 10%">Call From</th>
            <th style="width: 10%">Call To</th>
            <th style="width: 10%">Call Duration</th>
            <th style="width: 10%">Talk Duration</th>
            <th style='width: 10%'>Status</th>
          </tr>
        </thead>
        <tbody style="height: 43%;display:block;overflow-y:scroll" class="list-group" id="callstable">
        </tbody>
       </table>
      </div>
    </div>
  </div>
</div>
</body>
<script>
  readtable();
  function readtable() {
    var txt="";
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
          document.getElementById("callstable").innerHTML=this.responseText;
          // myObj = JSON.parse(this.responseText);
      }
    };
    xmlhttp.open("POST", "readtable.php", true);
    xmlhttp.send();
  }

  function searchtable(){
    var data = {
      timefrom:$("#timefrom").val(),
      timeto:$("#timeto").val(),
      callfrom:$("#callfrom").val(),
      callto:$("#callto").val(),
      callduration:$("#callduration").val(),
      talkduration:$("#talkduration").val(),
      status:($("#status").val())=='All'?"":$("#status").val(),
      drunk:$("#drunk").val(),
      communicationtype:$("#communicationtype").val(),
      pincode:$("#pincode").val(),
      number_fuzzy:$("#number_fuzzy:checked").val()?1:0,
    };
    $.ajax({
        type: "POST",
        // dataType: "json",
        url: "./readsearchtable.php",
        data: data,
        // contentType: "application/json; charset=utf-8",
        success: function(data){
          // console.log(JSON.parse(data));
          document.getElementById("callstable").innerHTML=data;
        },
        error: function(e){
            console.log(e.message);
        }
    });
  }
</script>
</html>