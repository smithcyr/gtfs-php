module.exports = function(grunt) {

    // Build calendar file.
  grunt.registerTask('calendar', 'Builds calendar.txt.', function() {
    // Include calendar file.
    grunt.file.copy('build/calendar.csv', 'build/bin/calendar.txt');
    grunt.log.writeln("calendar.txt created.");
  });
};
