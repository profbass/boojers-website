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
      files: ['grunt.js', 'public_html/js/*.js']
    },

    min: {
      dist: {
        src: [
          'public_html/js/lib/jquery.js',
          'public_html/js/lib/jquery.easing.1.3.js',
          'public_html/js/fancybox/jquery.fancybox.js',
          'public_html/js/jquery.quicksand.js',
          'public_html/js/jquery.boojGallery.js',
          'public_html/js/jquery.home_gallery.js',
          'public_html/js/twitter-bootstrap/bootstrap-collapse.js', 
          'public_html/js/twitter-bootstrap/bootstrap-transition.js', 
          'public_html/js/application.js'
        ],
        dest: 'public_html/dist/app.min.js'
      }
    },

    less: {
      yuicompress: {
        options: {
          yuicompress: true
        },
        files: {
          'public_html/dist/style.min.css': ['less/style.less']
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
        files: 'public_html/js/**.js',
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
