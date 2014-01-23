module.exports = function(grunt) {

// Generic default content.
var config = {
  // read the package.json
    // pkg will contain a reference to out pakage.json file use of which we will see later
  pkg: grunt.file.readJSON('package.json')
  // Background shell commands.
  /*bgShell: {
    defaults: {
      bg: true
    },
    // GTFS validator command in python.
    // Requires feedvalidator.py from
    // https://code.google.com/p/googletransitdatafeed/ added to your $PATH.
    gtfsValidator: {
      cmd: 'feedvalidator.py exports'
    },
    gtfsScheduleViewer: {
      cmd: 'schedule_viewer.py --feed_filename exports',
      bg: false
    }
  }*/
};

// Combine the options files into one configuration.
grunt.util._.extend(config, loadConfig('./tasks/options/'));

grunt.initConfig(config);
// Load the configuration.
grunt.log.writeln(JSON.stringify(config));
//grunt.log.writeln(grunt.config.get('pkg.name'));


/**
 * Concatinate all config files.
 * @param  {string} path
 * @return {object}
 */
function loadConfig(path) {
  var glob = require('glob');
  var object = {};
  var key;

  glob.sync('*', {cwd: path}).forEach(function(option) {
    key = option.replace(/\.js$/,'');
    object[key] = require(path + option);
  });
  //grunt.log.write(JSON.stringify(object));
  return object;
}

  grunt.loadNpmTasks('grunt-bg-shell');
  grunt.loadNpmTasks('grunt-http');

  // Run all the node stuff.
  //grunt.registerTask('run', ['bgShell:runNode']);

  grunt.loadTasks('tasks');

}
