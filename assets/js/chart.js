const labels = [
    'Exam1',
    'Exam2',
    'Exam3',
    'Exam4',
    'Exam5',
    'Exam6',
  ];

  const data = {
    labels: labels,
    datasets: [{
      label: 'Attendace',
      backgroundColor: 'rgba(52, 235, 146)',
      borderColor: 'rgba(52, 235, 146)',
      data: [40, 20, 40, 20, 40, 20, 40],
    },
    {
      label: 'Pass',
      backgroundColor: 'rgb(52, 101, 235)',
      borderColor: 'rgb(52, 101, 235)',
      data: [20, 50, 10, 50, 10, 50, 10],
    }
  ]
  };

  const config = {
    type: 'line',
    data: data,
    options: {
      responsive: true
    }
  };

  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );


  const data1 = {
  labels: ['Exam1', 'Exam2', 'Exam3', 'Exam4', 'Exam5'],
  datasets: [
    {
      label: 'Dataset 1',
      data: [20, 5, 20, 30, 25],
      backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
    }
  ]
};
  const config1 = {
  type: 'pie',
  data: data1,
  options: {
    responsive: true,
    maintainAspectRatio:false,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Chart.js Pie Chart'
      }
    }
  },
};

const myChart1 = new Chart(
    document.getElementById('myChart1'),
    config1
  );