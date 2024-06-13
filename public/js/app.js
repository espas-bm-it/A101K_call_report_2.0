   
      
document.addEventListener('DOMContentLoaded', function() {

  
  var dateRangeInput = $("#daterange");

  // Get date for initialization value of daterangepicker
  var today = new Date();
  var formattedToday = moment(today).format('DD-MM-YYYY');

  // Initialize daterangepicker with correct start and end dates
  dateRangeInput.on('show.daterangepicker', function(ev, picker){
    dateRangeInput.data('daterangepicker').setStartDate(formattedToday);
    dateRangeInput.data('daterangepicker').setEndDate(formattedToday);
  })

  // Apply correct dates to the daterangepicker
  dateRangeInput.on('apply.daterangepicker', function(ev, picker){
    let dataTable = $('#daterange_table').DataTable();

    
    let startDate = dateRangeInput.data('daterangepicker').startDate.format("DD-MM-YYYY");
    let endDate = dateRangeInput.data('daterangepicker').endDate.format("DD-MM-YYYY");
    dataTable.column(2).search(startDate + "|" + endDate, true, false).draw();

  }) 

    document.getElementById('reset-btn').addEventListener('click', function() {
      
        console.log('Clicked on resetFilters');

        
        // Clear all column filters
        let table = $('#daterange_table').DataTable();
        table.columns().search('').draw();
        table.search('').draw();

        

        // Resetting filters
        $("#selectColumn0").val('');
        $("#selectColumn6").val('');
        $("#selectColumn7").val('');
        $("#daterange").html('Datumsbereich');

        
        // Resetting manual search
        let daterangeTableFilter = document.getElementById("daterange_table_filter");
        if (daterangeTableFilter) {
            let inputElement = daterangeTableFilter.querySelector("label input");
            if (inputElement) {
                inputElement.value = '';
            }
          }

        

        // Hide charts & servicerating
        let canvasElementBar = document.getElementById("myBarChart");
        let canvasElementPie = document.getElementById("myPieChart");
        let canvasElementOutgoing = document.getElementById("outgoingChart");
        let canvasElementOutgoingPie = document.getElementById("outgoingChartPie");
        let canvasElementHistoryChart = document.getElementById("myTimeHistoryChart");


        canvasElementBar.style.display = "none";
        canvasElementPie.style.display = "none";
        canvasElementOutgoing.style.display = "none";
        canvasElementOutgoingPie.style.display = "none";
        canvasElementHistoryChart.style.display = "none";

        let serviceRatingElement = document.getElementById("serviceRating")
        serviceRatingElement.style.display = "none";
        // ajax reload
        table.ajax.reload();
    });
});


document.addEventListener('DOMContentLoaded', function(){  
  let myBarChart;
  let myPieChart;
  let outgoingChart;
  let outgoingChartPie;
  let timeHistoryChart;

  document.getElementById('graphicalDisplay').addEventListener('click', function(){
    // Initialize the charts once the button is clicked
    let dataTable = $("#daterange_table").DataTable();
    let ctxBar = document.getElementById('myBarChart').getContext('2d');    
    let ctxPie = document.getElementById('myPieChart').getContext('2d');
    let ctxOutgoing = document.getElementById('outgoingChart').getContext('2d');
    let ctxOutgoingPie = document.getElementById('outgoingChartPie').getContext('2d');   
    let ctxTimeHistoryChart = document.getElementById('myTimeHistoryChart').getContext('2d');

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
        updateOutgoingChartPie(response);
        updateTimeHistoryChart(response);
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

    // BarChart
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
              backgroundColor: ['rgb(135, 200, 90, 0.8)', 'rgb(230, 100, 70, 0.8)'],
              borderColor: ['rgb(135, 200, 90, 0.4)', 'rgb(230, 100, 70, 0.4)'],
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
  
      // Calculate data counts from the response
      response.data.forEach(function(item) {
          if (item.CallStatus == "Verpasst") {
              unanswered++;
          } else if (stringToNumber(item.RingingDuration) > 30 && item.CallStatus == "Angenommen") {
              aboveThirty++;
          } else if (stringToNumber(item.RingingDuration) < 10 && item.CallStatus == "Angenommen") {
              belowTen++;
          } else if (stringToNumber(item.RingingDuration) < 20 && item.CallStatus == "Angenommen") {
              belowTwenty++;
          } else if (stringToNumber(item.RingingDuration) <= 30 && item.CallStatus == "Angenommen") {
              belowThirty++;
          }
      });
  
      // Get canvas element
      let canvasElement = document.getElementById("myPieChart");
  
      if (unanswered === 0 && aboveThirty === 0 && belowThirty === 0 && belowTwenty === 0 && belowTen === 0) {
          // Hide canvas if no data
          canvasElement.style.display = "none";
      } else if (myPieChart) {
          // Update existing chart if it exists
          myPieChart.data.datasets[0].data = [unanswered, aboveThirty, belowThirty, belowTwenty, belowTen];
          myPieChart.update();
          canvasElement.style.display = "";
      } else {
          // Create new chart if it doesn't exist
          canvasElement.style.display = "";
          let ctxPie = canvasElement.getContext('2d');
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
                          position: 'bottom',
                          labels: {
                              boxWidth: 20
                          }
                      }
                  }
              }
          });
      }
  }
    // OutgoingPiechart
    function updateOutgoingChartPie(response) {
      let unanswered1 = 0;
      let answered1 = 0;
      

      response.data.forEach(function(item) {
        if ((item.CommunicationType == "PAusgehend" || item.CommunicationType == "TSAusgehend") && item.CallDuration == '00:00:00') {
            unanswered1++;
        } else if ((item.CommunicationType == "PAusgehend" || item.CommunicationType == "TSAusgehend")) {
            answered1++;
        } 
    });

      // Get canvas element and set display later to show/hide element
      let canvasElement = document.getElementById("outgoingChartPie");
      

      if(unanswered1 === 0 && answered1 === 0){
        canvasElement.style.display = "none";
      } else if (outgoingChartPie) {
        outgoingChartPie.data.datasets[0].data = [unanswered1, answered1];
        outgoingChartPie.update();
        canvasElement.style.display = "";
      } else {
        canvasElement.style.display = "";
        outgoingChartPie = new Chart(ctxOutgoingPie, {
          type: 'pie',
          data: {
            labels: ["nicht angenommen", "angenommen"],
            datasets: [{
              label: '# of Calls',
              data: [unanswered1, answered1],
              backgroundColor: ['rgba(237, 14, 14, 0.8)', 'rgba(12, 194, 10, 0.8)'],
              borderColor: ['rgba(237, 14, 14, 0.8)', 'rgba(12, 194, 10, 0.8)'],
              borderWidth: 1
            }]
          },
          options: {
            plugins: {
              title: {
                display: true,
                text: 'Reaktionszeit Client'
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
        if (item.CallDuration !== "00:00:00" && (item.CommunicationType == "PAusgehend" || item.CommunicationType == "TSAusgehend")) {
          countAngenommen++;
         } else if (item.CallDuration === "00:00:00" && (item.CommunicationType == "PAusgehend" || item.CommunicationType == "TSAusgehend")) {
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
        outgoingChart.data.datasets[0].data = [countAngenommen, countNotAngenommen];
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
              backgroundColor: ['rgba(12, 194, 10, 0.8)', 'rgb(230, 100, 70, 0.8)'],
              borderColor: ['rgba(12, 194, 10, 0.8)', 'rgb(230, 100, 70, 0.4)'],
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

        // TimeHistoryChart
function updateTimeHistoryChart(response) {
  // Variables to compute logic
  let countByTimeOfDay = {}; // Object to store counts for each time of day
  let countByTimeOfDayAccepted = {}; // Object to store counts for each time of day where callStatus === accepted currently not in use
  let countByTimeOfDayNotAccepted = {}; // Object to store counts for each time of day where callStatus === notAccepted

  // Initialize count objects
  for (let i = 0; i < 24; i++) {
    let hour = i;
    countByTimeOfDay[hour] = 0;
    countByTimeOfDayAccepted[hour] = 0;
    countByTimeOfDayNotAccepted[hour] = 0;
  }

  // Logic
  response.data.forEach(function(item) {
      // Extract the hour from the timestamp without rounding
    let timeParts = item.Time.split(':');
    let hour = parseInt(timeParts[0]);
    
    
    // Format the time as HH:00
    let timeOfDay = `${hour}`;


    // Increment the count for the corresponding time of day
    if (!countByTimeOfDay[timeOfDay]) {
        countByTimeOfDay[timeOfDay] = 0;
    }

    countByTimeOfDay[timeOfDay]++;
    if(item.CallStatus === 'Verpasst' ){
      countByTimeOfDayNotAccepted[timeOfDay]++
    } else if(item.CallStatus === 'Angenommen' ){
      countByTimeOfDayAccepted[timeOfDay]++
    }
});

  // Get canvas element and set display later to show/hide element
  let canvasElement = document.getElementById("myTimeHistoryChart");

  console.log(countByTimeOfDayNotAccepted);

  // Universal label for all datasets
  let labelsForAll = ['8', '9', '10', '11', '12', '13', '14', '15', '16', '17']

   
  
  // Prepare the chart data
  const chartData = {
    // Data for overall count of calls
      labels: labelsForAll, // Time of day labels
      datasets: [
        {
          label: 'Anzahl der Anrufe',
          data: labelsForAll.map(timeOfDay => countByTimeOfDay[timeOfDay]), // Counts for each time of day
          borderColor: 'rgba(75, 192, 192, 1)', // Line color
          backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill color under the line
          borderWidth: 1
        },
        {
          label: 'Anzahl der verpassten Anrufe',
          data: labelsForAll.map(timeOfDay => countByTimeOfDayNotAccepted[timeOfDay]),
          borderColor: 'rgba(255, 99, 132, 1)', // Line color
          backgroundColor: 'rgba(255, 99, 132, 0.2)', // Fill color under the line
          borderWidth: 1
        }
    ]
  };

  if (timeHistoryChart) {
      timeHistoryChart.data = chartData;
      timeHistoryChart.update();
      canvasElement.style.display = "";
  } else {
      canvasElement.style.display = "";
      timeHistoryChart = new Chart(ctxTimeHistoryChart, {
          type: 'line',
          data: chartData,
          options: {
              scales: {
                  x: {
                      type: 'category', // Use category scale for x-axis
                      labels: labelsForAll, // Time of day labels
                      
                      
                      
                  },
                  y: {
                      beginAtZero: true
                  }
              },
              plugins: {
                  legend: {
                      display: true,
                      position: 'bottom',
                      labels: {
                        boxWidth: 20
                      }
                  },
                  title: {
                      display: true,
                      text: 'Anzahl der totalen und verpassten Anrufe nach Uhrzeit sortiert' // Chart title
                  }
              }
          }
      });
  }
}
  });
});
    
    
