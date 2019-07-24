var c1 = document.getElementById("c1");
var parent = document.getElementById("p1");
c1.width = parent.offsetWidth - 40;
c1.height = parent.offsetHeight - 40;

var data1 = {
  labels : ["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15"],
  datasets : [
    {
      fillColor : "rgba(63, 81, 181,.1)",
      strokeColor : "rgba(63, 81, 181,1)",
      pointColor : "#123",
      pointStrokeColor : "rgba(63, 81, 181,1)",
      data : [1,2,0.5,0.8,0.8,0.8,0.5,0.5,0.5,0.5,2,2,0.8,0.8,0.8]
    }
  ]
}

var options1 = {
  scaleFontColor : "rgba(0,0,0,1)",
  scaleLineColor : "rgba(0,0,0,1)",
  scaleGridLineColor : "transparent",
  bezierCurve : false,
  scaleOverride : true,
  scaleSteps : 15,
  scaleStepWidth : 0.1,
  scaleStartValue : 0
}

new Chart(c1.getContext("2d")).Line(data1,options1)