module.exports = function(grunt) {

  // Build agency file.
  grunt.registerTask('agency', 'Builds agency.txt.', function() {
    // Include Agency file.
    grunt.file.copy('build/agency.csv', 'build/bin/agency.txt');
    grunt.log.writeln("Agency.txt created.");
  });
};
