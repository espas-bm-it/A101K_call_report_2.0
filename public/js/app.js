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
  let myChart = null;
  document.getElementById('ajaxSee').addEventListener('click', function(){
    // Initialize the chart once when the button is clicked
    let dataTable = $("#daterange_table").DataTable();
    let ctx = document.getElementById('myChart').getContext('2d');    
    
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
      let canvasElement = document.getElementById("myChart");
      canvasElement.style.display = "";

      // Update the existing chart instance if it exists
      if (myChart) {
        myChart.data.datasets[0].data = [countAngenommen, countNotAngenommen];
        myChart.update();
        
      } else{

      // Create the chart
      myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["Angenommen", "Verpasst"],
          datasets: [{
            label: '# of Calls',
            data: [countAngenommen, countNotAngenommen],
            backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
            borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
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
