module.exports= (grunt)->
  PROJECT_NAME= "art_custom_post_type"
  grunt.initConfig
    pkg: grunt.file.readJSON 'package.json'
    notify_hooks:
      options: enabled: on, success: on, title: PROJECT_NAME
    esteWatch:
      options:
        dirs: [
          'lib/**'
          'test/tests/**'
          'test/theme/**'
        ]
        livereload: enabled: yes, extensions: [
          'php', 'haml', 'html'
        ]
      "php": (path)->
        ["test"] unless path.match /^dev\//
    shell:
      phpunit:
        command: "phpunit --configuration test/phpunit.xml"
        options:
          failOnError: yes

  require("matchdep").filterDev("grunt-*").forEach(grunt.loadNpmTasks)
  grunt.task.run 'notify_hooks'

  grunt.registerTask 'test',['shell:phpunit']
  grunt.registerTask 'default', [
    'esteWatch'
    'test'
  ]
