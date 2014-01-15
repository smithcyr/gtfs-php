module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Build agency file.
  grunt.registerTask('stop_times_move', 'Moves stop_times from exports into build. This is only necessary for testing.', function() {
    // Include Agency file.
    grunt.file.copy('exports/coralville_stop_times.txt', 'build/coralville_stop_times.txt');
    grunt.file.copy('exports/iowacity_stop_times.txt', 'build/iowacity_stop_times.txt');
    grunt.file.copy('exports/uiowa_stop_times.txt', 'build/uiowa_stop_times.txt');
    grunt.log.writeln("Stop time files moved from exports into build.");
  });


    // Build agency file.
  grunt.registerTask('stop_times', 'Concat stop times files together.', ['concat:dist']);
};


//grunt.registerTask('validate', 'Validates GTFS files.', ['bgShell:gtfsValidator']);
