/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    meta: {
      version: '0.1.0',
      banner: '/*! Flight Apps - v<%= meta.version %> - ' +
        '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
        '* http://booj.com/\n' +
        '* Copyright (c) <%= grunt.template.today("yyyy") %> ' +
        'Booj dba Active Website LLC.; Licensed MIT */'
    },

    lint: {
      files: ['grunt.js', 'public/js/*.js']
    },

    min: {
      dist: {
        src: [
          'public/js/lib/jquery.js',
          'public/js/lib/mustache.js',
          'public/js/twitter-bootstrap/bootstrap-collapse.js', 
          'public/js/twitter-bootstrap/bootstrap-carousel.js',
          'public/js/twitter-bootstrap/bootstrap-transition.js', 
          'public/js/hover_intent.js', 
          'public/js/jquery.menu.js', 
          'public/js/application.js'
        ],
        dest: 'public/dist/app.min.js'
      }
    },

    less: {
      yuicompress: {
        options: {
          yuicompress: true
        },
        files: {
          'public/dist/style.min.css': ['less/style.less']
        }
      }
    },

    watch: {
      less: {
        files: 'less/**.less',
        tasks: ['less'],
        options: {
          interrupt: true
        }
      },
      scripts: {
        files: 'public/js/**.js',
        tasks: ['lint', 'min'],
        options: {
          interrupt: true
        }
      }
    },

    jshint: {
      options: {
        validthis: true,
        laxcomma : true,
        laxbreak : true,
        browser  : true,
        eqnull   : true,
        debug    : true,
        devel    : true,
        boss     : true,
        expr     : true,
        asi      : true
      },
      globals: {
        jQuery: true,
        google: true,
        Mustache: true,
      }
    }

  });

  // Default task.
  grunt.registerTask('default', 'lint less min');
  grunt.loadNpmTasks('grunt-contrib');
};
