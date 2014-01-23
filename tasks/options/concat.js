module.exports = {
  dist: {
    src: [
      'build/header_stop_times.txt',
      'build/stoptimes/*.csv'
    ],
    dest: 'build/bin/stop_times.txt',
  },
  trips: {
    src: [
      'build/header_trips.txt',
      'build/trips/coralville_trips.csv',
      'build/trips/iowacity_trips.csv',
      'build/trips/uiowa_trips.csv'
    ],
    dest: 'build/bin/trips.txt',
  },
}
