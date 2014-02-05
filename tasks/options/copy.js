module.exports = {
  stoptimes: {
    files: [
      {
        expand: true,
        flatten: true,
        src: ['exports/stoptimes/**'],
        dest: 'build/stoptimes/',
        filter: 'isFile'
      }
    ]
  },
  trips: {
    files: [
      {
        expand: true,
        flatten: true,
        src: ['exports/trips/**'],
        dest: 'build/trips/',
        filter: 'isFile'
      }
    ]
  }
}
