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
      files: ['grunt.js', 'www/js/*.js']
    },

    min: {
      dist: {
        src: [
          'www/js/lib/jquery.js',
          'www/js/lib/jquery.easing.1.3.js',
          'www/js/fancybox/jquery.fancybox.js',
          'www/js/jquery.quicksand.js',
          'www/js/jquery.boojGallery.js',
          'www/js/jquery.home_gallery.js',
          'www/js/twitter-bootstrap/bootstrap-collapse.js', 
          'www/js/twitter-bootstrap/bootstrap-transition.js', 
          'www/js/application.js'
        ],
        dest: 'www/dist/app.min.js'
      }
    },

    less: {
      yuicompress: {
        options: {
          yuicompress: true
        },
        files: {
          'www/dist/style.min.css': ['less/style.less']
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
        files: 'www/js/**.js',
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
        Mustache: true
      }
    }

  });

  // Default task.
  grunt.registerTask('default', 'lint less min');
  grunt.loadNpmTasks('grunt-contrib');
};
