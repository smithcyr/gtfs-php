module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-concat');

  // Build agency file.
  grunt.registerTask('trips_move', 'Moves trips from exports into build. This is only necessary for testing.', function() {
    // Include Agency file.
    grunt.file.copy('exports/trips/coralville_trips.csv', 'build/trips/coralville_trips.csv');
    grunt.file.copy('exports/trips/iowacity_trips.csv', 'build/trips/iowacity_trips.csv');
    grunt.file.copy('exports/trips/uiowa_trips.csv', 'build/trips/uiowa_trips.csv');
    grunt.log.writeln("Trip files moved from exports into build.");
  });


    // Build agency file.
  grunt.registerTask('trips', 'Concat trips files together.', ['concat:trips']);
};


//grunt.registerTask('validate', 'Validates GTFS files.', ['bgShell:gtfsValidator']);
