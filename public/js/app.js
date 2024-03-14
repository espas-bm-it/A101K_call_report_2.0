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


document.addEventListener('DOMContentLoaded', function(){  
  let myBarChart;
  let myPieChart;

  document.getElementById('graphicalDisplay').addEventListener('click', function(){
    // Initialize the charts once the button is clicked
    let dataTable = $("#daterange_table").DataTable();
    let ctxBar = document.getElementById('myBarChart').getContext('2d');    
    let ctxPie = document.getElementById('myPieChart').getContext('2d');
    let ajaxParams = dataTable.ajax.params();
    ajaxParams.length = -1;

    $.ajax({
      url: dataTable.ajax.url(),
      method: 'GET',
      data: ajaxParams,
      dataType: 'json',
      success: function(response) {
        // Update the chart with the new data
        updateUIBarChart(response);
        updateUIPieChart(response);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
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

    function updateUIBarChart(response) {
      let countAngenommen = 0;
      let countNotAngenommen = 0;
      
      response.data.forEach(function(item) {
        if (item.CallStatus === "angenommen") {
          countAngenommen++;
        } else {
          countNotAngenommen++;
        }
      });
      
      // Logic for service evaluation
      let serviceRating = 100 / ((countAngenommen + countNotAngenommen) / countAngenommen);
      let serviceRatingElement = document.getElementById("serviceRating")
      serviceRatingElement.style.display = "";
      serviceRatingElement.innerHTML = "Erreichbarkeit: " + parseInt( serviceRating ) + "%";


      let canvasElement = document.getElementById("myBarChart");
      canvasElement.style.display = "";

      if (myBarChart) {
        myBarChart.data.datasets[0].data = [countAngenommen, countNotAngenommen];
        myBarChart.update();
      } else {
        myBarChart = new Chart(ctxBar, {
          type: 'bar',
          data: {
            labels: ["Angenommen", "Verpasst"],
            datasets: [{
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
            },
            plugins: {
              legend: {
                display: false // Set display to false to hide the legend
              }
            }
          }
        });
      }
    }
    
    function updateUIPieChart(response) {
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

      let canvasElement = document.getElementById("myPieChart");
      canvasElement.style.display = "";

      if (myPieChart) {
        myPieChart.data.datasets[0].data = [unanswered, aboveThirty, belowThirty, belowTwenty, belowTen];
        myPieChart.update();
      } else {
        myPieChart = new Chart(ctxPie, {
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
          }
        });
      }
    }
  });
});
    
    
