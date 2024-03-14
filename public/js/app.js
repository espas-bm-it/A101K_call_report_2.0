document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reset-btn').addEventListener('click', function() {
        console.log('Clicked on resetFilters');
        // Clear all column filters
        let table = $('#daterange_table').DataTable();
        table.columns().search('').draw();

        // Resetting filters
        $("#selectColumn0").val('');
        $("#selectColumn6").val('');
        $("#daterange").html('Datumsbereich auswählen');
    });
});


// Button for BarChart
document.addEventListener('DOMContentLoaded', function(){  
  let myBarChart = null;
  document.getElementById('barChartButton').addEventListener('click', function(){
    // Initialize the chart once when the button is clicked
    let dataTable = $("#daterange_table").DataTable();
    let ctx = document.getElementById('myBarChart').getContext('2d');    
    
    let ajaxParams = dataTable.ajax.params();
    ajaxParams.length = -1;

    $.ajax({
      url: dataTable.ajax.url(),
      method: 'GET',
      data: ajaxParams,
      dataType: 'json',
      success: function(response) {
        // Update the chart with the new data
        updateUI(response);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
      }
    });

    function updateUI(response) {
      
      let countAngenommen = 0;
      let countNotAngenommen = 0;

      response.data.forEach(function(item) {
        if (item.CallStatus === "angenommen") {
          countAngenommen++;
        } else {
          countNotAngenommen++;
        }
      });
      
      // show canvas display 
      let canvasElement = document.getElementById("myBarChart");
      canvasElement.style.display = "";

      // Update the existing chart instance if it exists
      if (myBarChart) {
        myBarChart.data.datasets[0].data = [countAngenommen, countNotAngenommen];
        myBarChart.update();
        
      } else{

      // Create the chart
      myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["Angenommen", "Verpasst"],
          datasets: [{
            label: '# of Calls',
            data: [countAngenommen, countNotAngenommen],
            backgroundColor: ['rgba(12, 194, 10, 0.8)', 'rgba(237, 14, 14, 0.8)'],
            borderColor: ['rgba(12, 194, 10, 0.8)', 'rgba(237, 14, 14, 0.8)'],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    }
    }
  });
});

// Button for PieChart
document.addEventListener('DOMContentLoaded', function(){
  let myPieChart;
  document.getElementById('pieChartButton').addEventListener('click', function(){
// Initialize the chart once when the button is clicked
let dataTable = $("#daterange_table").DataTable();
let ctx = document.getElementById('myPieChart').getContext('2d');    

let ajaxParams = dataTable.ajax.params();
ajaxParams.length = -1;

$.ajax({
  url: dataTable.ajax.url(),
  method: 'GET',
  data: ajaxParams,
  dataType: 'json',
  success: function(response) {
    // Update the chart with the new data
    updateUI(response);
  },
  error: function(xhr, status, error) {
    console.error('Error:', error);
  }
});

function updateUI(response) {
  
  let unanswered = 0;
  let aboveThirty = 0;
  let belowThirty = 0;
  let belowTwenty = 0;
  let belowTen = 0;

  response.data.forEach(function(item) {
    if ( item.CallStatus == "verpasst") {
      unanswered++;
    } else if (stringToNumber(item.RingingDuration) > 30 && item.CallStatus == "angenommen") {
      aboveThirty++;
    } else if (stringToNumber(item.RingingDuration) < 10 && item.CallStatus == "angenommen"){
      belowTen++;
    } else if (stringToNumber(item.RingingDuration) < 20 && item.CallStatus == "angenommen"){
      belowTwenty++;
    } else if (stringToNumber(item.RingingDuration) < 30 && item.CallStatus == "angenommen"){
      belowThirty++;
    } 
  });

  // Function to convert item.RingingDuration to a usable number
  function stringToNumber(timeString){
    // Split the string into usable parts
    let parts = timeString.split(":");
    // Multiplication to represent the split unit of time correctly
    let totalSeconds = parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
    return totalSeconds;
  }
  
  // show canvas display 
  let canvasElement = document.getElementById("myPieChart");
  canvasElement.style.display = "";

  // Update the existing chart instance if it exists
  if (myPieChart) {
    myPieChart.data.datasets[0].data = [countAngenommen, countNotAngenommen];
    myPieChart.update();
    
  } else{

  // Create the chart
  myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ["nicht angenommen", ">30 sek.", "<30 sek.", "<20 sek.", "<10 sek."],
      datasets: [{
        label: '# of Calls',
        data: [unanswered, aboveThirty, belowThirty, belowTwenty, belowTen],
        backgroundColor: ['rgba(237, 14, 14, 0.8)', 'rgba(248, 133, 3, 0.8)', 'rgba(220, 248, 3, 0.8)', 'rgba(123, 194, 10, 0.8)', 'rgba(12, 194, 10, 0.8)'],
        borderColor: ['rgba(237, 14, 14, 0.8)', 'rgba(248, 133, 3, 0.8)', 'rgba(220, 248, 3, 0.8)', 'rgba(123, 194, 10, 0.8)', 'rgba(12, 194, 10, 0.8)'],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
}
}
});
});