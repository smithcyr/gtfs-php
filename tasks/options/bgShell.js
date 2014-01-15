module.exports = {
  // Background shell commands.
    _defaults: {
      bg: true
    },
    // GTFS validator command in python.
    // Requires feedvalidator.py from
    // https://code.google.com/p/googletransitdatafeed/ added to your $PATH.
    gtfsValidator: {
      cmd: 'feedvalidator.py build/bin'
    },
    gtfsScheduleViewer: {
      cmd: 'schedule_viewer.py --feed_filename exports',
      bg: false
    }
}
