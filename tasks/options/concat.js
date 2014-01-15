module.exports = {
  options: {
  },
  dist: {
    src: [
      'build/header_stop_times.txt',
      'build/coralville_stop_times.txt',
      'build/iowacity_stop_times.txt',
      'build/uiowa_stop_times.txt'
    ],
    dest: 'build/bin/stop_times.txt',
  },
}
