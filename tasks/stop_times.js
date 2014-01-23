module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Build agency file.
  /*grunt.registerTask('stop_times_move', 'Moves stop_times from exports into build. This is only necessary for testing.', function() {
    // Include Agency file.
    grunt.file.copy('exports/stoptimes/coralville_10thst_stoptimes.csv', 'build/coralville_10thst_stoptimes.csv');
    grunt.log.writeln("Stop time files moved from exports into build.");
  });*/
  grunt.registerTask('stop_times_move', 'Moves stop_times from exports into build. This is only necessary for testing.', ['copy:stoptimes']);


    // Build agency file.
  grunt.registerTask('stop_times', 'Concat stop times files together.', ['concat:dist']);
};


//grunt.registerTask('validate', 'Validates GTFS files.', ['bgShell:gtfsValidator']);
