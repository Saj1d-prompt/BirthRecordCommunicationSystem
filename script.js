document.addEventListener('DOMContentLoaded', () => {
  // Initialize DataTable
  if (window.jQuery) {
    $('#advancedTable').DataTable();
  }

  // --- Monthly Comparison Logic ---
  const monthlyData = {
    jun: { births: 120, weight: 2.9, gest: 38.2 },
    jul: { births: 135, weight: 3.0, gest: 38.5 },
    aug: { births: 150, weight: 3.1, gest: 38.9 }
  };

  function updateComparisonTable() {
    const m1 = document.getElementById("month1").value;
    const m2 = document.getElementById("month2").value;
    const data1 = monthlyData[m1];
    const data2 = monthlyData[m2];

    const percentChange = (v1, v2) => {
      if (v1 === 0) return '∞%';
      return ((v2 - v1) / v1 * 100).toFixed(1) + '%';
    };

    document.getElementById("births1").innerText = data1.births;
    document.getElementById("births2").innerText = data2.births;
    document.getElementById("birthsChange").innerText = percentChange(data1.births, data2.births);

    document.getElementById("weight1").innerText = data1.weight.toFixed(1);
    document.getElementById("weight2").innerText = data2.weight.toFixed(1);
    document.getElementById("weightChange").innerText = percentChange(data1.weight, data2.weight);

    document.getElementById("gest1").innerText = data1.gest.toFixed(1);
    document.getElementById("gest2").innerText = data2.gest.toFixed(1);
    document.getElementById("gestChange").innerText = percentChange(data1.gest, data2.gest);
  }

  document.getElementById("month1").addEventListener("change", updateComparisonTable);
  document.getElementById("month2").addEventListener("change", updateComparisonTable);
  updateComparisonTable();

  // --- Chart 1: Gender Trend (Stacked Bar) ---
  new Chart(document.getElementById('stackedBarChart'), {
    type: 'bar',
    data: {
      labels: ['Aug 1', 'Aug 2', 'Aug 3', 'Aug 4'],
      datasets: [
        { label: 'Male', data: [5, 6, 4, 7], backgroundColor: '#4e79a7' },
        { label: 'Female', data: [4, 5, 3, 6], backgroundColor: '#f28e2c' },
        { label: 'Other', data: [1, 1, 0, 1], backgroundColor: '#e15759' }
      ]
    },
    options: {
      responsive: true,
      scales: {
        x: { stacked: true },
        y: { stacked: true }
      }
    }
  });

  // --- Chart 2: Simulated Weight Boxplot (Bar) ---
  new Chart(document.getElementById('weightBoxChart'), {
    type: 'bar',
    data: {
      labels: ['Weight Distribution (kg)'],
      datasets: [
        { label: 'Min', data: [2.5], backgroundColor: '#c9c9c9' },
        { label: 'Q1', data: [2.8], backgroundColor: '#a0d2eb' },
        { label: 'Median', data: [3.0], backgroundColor: '#4fa6d7' },
        { label: 'Q3', data: [3.2], backgroundColor: '#1b75bc' },
        { label: 'Max', data: [3.5], backgroundColor: '#124e78' }
      ]
    },
    options: {
      indexAxis: 'y',
      scales: {
        x: { beginAtZero: true }
      }
    }
  });

  // --- Chart 3: Heatmap-like Birth Count Bar ---
  new Chart(document.getElementById('heatmapChart'), {
    type: 'bar',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: 'Births per Day',
        data: [6, 7, 5, 8, 9, 4, 3],
        backgroundColor: function (context) {
          const value = context.raw;
          if (value >= 8) return '#d7191c';
          if (value >= 6) return '#fdae61';
          if (value >= 4) return '#abd9e9';
          return '#2c7bb6';
        }
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true }
      }
    }
  });

  // --- Chart 4: Scatter Plot (Gestation vs Weight) ---
  new Chart(document.getElementById('scatterChart'), {
    type: 'scatter',
    data: {
      datasets: [{
        label: 'Weight vs Gestation',
        data: [
          { x: 36, y: 2.8 },
          { x: 37, y: 3.0 },
          { x: 38, y: 2.9 },
          { x: 39, y: 3.2 },
          { x: 40, y: 3.1 }
        ],
        backgroundColor: '#5e4fa2'
      }]
    },
    options: {
      scales: {
        x: { title: { display: true, text: 'Gestation (weeks)' }},
        y: { title: { display: true, text: 'Weight (kg)' }}
      }
    }
  });

  // --- Chart 5: Gender Distribution Pie Chart ---
  new Chart(document.getElementById('genderPieChart'), {
    type: 'pie',
    data: {
      labels: ['Male', 'Female', 'Other'],
      datasets: [{
        data: [2, 1, 1], // From sample table: 2 Male, 1 Female, 1 Other
        backgroundColor: ['#4e79a7', '#f28e2c', '#e15759'],
        borderColor: '#fff',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        tooltip: {
          callbacks: {
            label: function (context) {
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const value = context.parsed;
              const percent = ((value / total) * 100).toFixed(1);
              return `${context.label}: ${value} (${percent}%)`;
            }
          }
        }
      }
    }
  });
});
