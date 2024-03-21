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
  let outgoingChart;

  document.getElementById('graphicalDisplay').addEventListener('click', function(){
    // Initialize the charts once the button is clicked
    let dataTable = $("#daterange_table").DataTable();
    let ctxBar = document.getElementById('myBarChart').getContext('2d');    
    let ctxPie = document.getElementById('myPieChart').getContext('2d');
    let ctxOutgoing = document.getElementById('outgoingChart').getContext('2d');
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
        updateOutgoingChart(response);
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
       if (item.CallStatus === "Angenommen") {
         countAngenommen++;
        } else if (item.CallStatus === "Verpasst") {
         countNotAngenommen++;
        }
       });
      
      // Logic for service evaluation
      let serviceRating = 100 / ((countAngenommen + countNotAngenommen) / countAngenommen);
      let serviceRatingElement = document.getElementById("serviceRating")
      
      // Get HTML element and set display to show/hide element
      serviceRatingElement.innerHTML = "Service Level: " + parseInt( serviceRating ) + "%";

      // Get canvas element and set display later to show/hide element
      let canvasElement = document.getElementById("myBarChart");
      
      if (countAngenommen === 0 && countNotAngenommen === 0){
        canvasElement.style.display = "none";
        serviceRatingElement.style.display = "none";
      } else if (myBarChart) {
        myBarChart.data.datasets[0].data = [countAngenommen, countNotAngenommen];
        myBarChart.update();

        canvasElement.style.display = "";
        serviceRatingElement.style.display = "";
      } else {
        
      canvasElement.style.display = "";
      serviceRatingElement.style.display = "";


        myBarChart = new Chart(ctxBar, {
          type: 'bar',
          data: {
            labels: ["Angenommen", "Verpasst"],
            datasets: [{
              data: [countAngenommen, countNotAngenommen],
              barPercentage: 0.6,
              backgroundColor: ['rgba(12, 194, 10, 0.8)', 'rgba(237, 14, 14, 0.8)'],
              borderColor: ['rgba(12, 194, 10, 0.8)', 'rgba(237, 14, 14, 0.8)'],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              x: {
                grid: {
                  display: false
                }
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: false
                }
              }
            },
            plugins: {
              legend: {
                display: false // Set display to false to hide the legend
              },
              title: {
                  display:true,
                  text: 'Einkommende Anrufe im ausgewählten Zeitfenster'
              }
            }
          }
        });
      }
    }
    
    // Piechart
    function updateUIPieChart(response) {
      let unanswered = 0;
      let aboveThirty = 0;
      let belowThirty = 0;
      let belowTwenty = 0;
      let belowTen = 0;

      response.data.forEach(function(item) {
        if (item.CallStatus == "Verpasst") {
            unanswered++;
        } else if (stringToNumber(item.RingingDuration) > 30 && item.CallStatus == "Angenommen" ) {
            aboveThirty++;
        } else if (stringToNumber(item.RingingDuration) < 10 && item.CallStatus == "Angenommen" ) {
            belowTen++;
        } else if (stringToNumber(item.RingingDuration) < 20 && item.CallStatus == "Angenommen" ) {
            belowTwenty++;
        } else if (stringToNumber(item.RingingDuration) <= 30 && item.CallStatus == "Angenommen" ) {
            belowThirty++;
        } 
    });

      // Get canvas element and set display later to show/hide element
      let canvasElement = document.getElementById("myPieChart");
      

      if(unanswered === 0 && aboveThirty === 0 && belowThirty === 0 && belowTwenty === 0 && belowTen === 0){
        canvasElement.style.display = "none";
      } else if (myPieChart) {
        myPieChart.data.datasets[0].data = [unanswered, aboveThirty, belowThirty, belowTwenty, belowTen];
        myPieChart.update();
        canvasElement.style.display = "";
      } else {
        canvasElement.style.display = "";
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
          },
          options: {
            plugins: {
              title: {
                display: true,
                text: 'Reaktionszeit Telefonservice'
              },
              legend: {
                position: 'right',
                labels: {
                  boxWidth: 20
                }
              }
            }
          }
        });
      }
    }
    // Chart outgoing calls
    function updateOutgoingChart(response){
      // Variables to compute logic
      let countAngenommen = 0;
      let countNotAngenommen = 0;
      // Logic
      response.data.forEach(function(item) {
        if (item.CallDuration !== "00:00:00" && item.CommunicationType === "Ausgehend") {
          countAngenommen++;
         } else if (item.CallDuration === "00:00:00" && item.CommunicationType === "Ausgehend") {
          countNotAngenommen++;
         }
        });
      // Get canvas element and set display later to show/hide element
      let canvasElement = document.getElementById("outgoingChart");
      
      // generate or update chart
      if(countAngenommen === 0 && countNotAngenommen ===0 ){
        canvasElement.style.display = "none";
      } else if(outgoingChart) {
        canvasElement.style.display = "";
        outgoingChart.datasets[0].data = [countAngenommen, countNotAngenommen];
        outgoingChart.update();
        
      } else {
        canvasElement.style.display = "";
        outgoingChart = new Chart(ctxOutgoing, {
          type: 'bar',
          data: {
            labels: ["Angenommen", "Verpasst"],
            datasets: [{
              data: [countAngenommen, countNotAngenommen],
              barPercentage: 0.6,
              backgroundColor: ['rgba(12, 194, 10, 0.8)', 'rgba(237, 14, 14, 0.8)'],
              borderColor: ['rgba(12, 194, 10, 0.8)', 'rgba(237, 14, 14, 0.8)'],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              x: {
                grid: {
                  display: false
                }
              },
              y: {
                beginAtZero: true,
                grid: {
                  display: false
                }
              }
            },
            plugins: {
              legend: {
                display: false // Set display to false to hide the legend
              },
              title: {
                  display:true,
                  text: 'Ausgehende Anrufe im ausgewählten Zeitfenster'
              }
            }
          }
        })
      }
    }
  });
});
    
    
