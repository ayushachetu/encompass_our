var data = {
  labels: ['Monday', 'Thusday', 'Wednesday', 'Thrusday', 'Friday', 'Sunday', 'Saturday'],
  series: [
    [5, 4, 3, 7,  10, 5, 4],
    [2, 10, 6, 7, 5, 3, 3]
  ]
};

var options = {
  seriesBarDistance: 10
};

var responsiveOptions = [
  ['screen and (max-width: 640px)', {
    seriesBarDistance: 5,
    axisX: {
      labelInterpolationFnc: function (value) {
        return value[0];
      }
    }
  }]
];

new Chartist.Bar('#overlapping-bars', data, options, responsiveOptions);