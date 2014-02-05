module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-concat');

  // move trips file.
  grunt.registerTask('trips_move', 'Moves trips from exports into build. This is only necessary for testing.', ['copy:trips']);



    // Build agency file.
  grunt.registerTask('trips', 'Concat trips files together.', ['concat:trips']);
};


//grunt.registerTask('validate', 'Validates GTFS files.', ['bgShell:gtfsValidator']);
