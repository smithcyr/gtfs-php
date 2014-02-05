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
      'build/trips/*.csv',
    ],
    dest: 'build/bin/trips.txt',
  },
}
