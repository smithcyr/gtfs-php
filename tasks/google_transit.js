module.exports = function(grunt) {
// Validation task using Python validation script.
  grunt.registerTask('validate', 'Validates GTFS files.', ['bgShell:gtfsValidator']);

  // View task using Python schedule viewer.
  grunt.registerTask('view', 'Initializing schedule viewer at http://localhost:8765/ - Ctl+c to quit and return.', ['bgShell:gtfsScheduleViewer']);
};
