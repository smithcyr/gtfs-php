module.exports = function(grunt) {
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Build agency file.
  grunt.registerTask('shapes_move', 'Moves shapes from exports into build. This is used to change filename.', function() {
    // Include Agency file.
    grunt.file.copy('build/shapes.csv', 'build/bin/shapes.txt');
    grunt.log.writeln("shapes moved from build to bin.");
  });
  //grunt.registerTask('stop_times_move', 'Moves stop_times from exports into build. This is only necessary for testing.', ['copy:stoptimes']);

};


//grunt.registerTask('validate', 'Validates GTFS files.', ['bgShell:gtfsValidator']);
