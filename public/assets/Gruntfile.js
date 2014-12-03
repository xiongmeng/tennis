/**
 * Created by Administrator on 14-12-3.
 */
module.exports = function (grunt) {
    var text = require('grunt-cmd-transport/tasks/lib/text').init(grunt);
    var script = require('grunt-cmd-transport/tasks/lib/script').init(grunt);

    var alias = [
        'rest/rest_cmd.js',
        'knockout/knockout.cmd.js',
        'knockout/plugins/mapping_debug.js',
        'knockout/plugins/knockout-switch-case.min.js',
        'knockout/plugins/knockout_plupload.js',
        'knockout/extend/area.js',
        'knockout/extend/phpTsToDate.js',
        'knockout/extend/options.js',
        'plupload/plupload.full.js',
        'plupload/plupload_cmd.js'
    ];

    function getFilePathByAlias(files, baseUrl) {
        return files.map(function(file) {
            return baseUrl + file;
        });
    }

    grunt.loadNpmTasks('grunt-cmd-transport');
    grunt.loadNpmTasks('grunt-cmd-concat');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-cachebuster');

    grunt.initConfig({
        clean: ['dist/**'],
        transport: {
            options: {
                parsers: {
                    '.tpl': [text.html2jsParser],
                    '.js': [script.jsParser]
                },
                include: 'all',
                debug: false,
                paths: ['js']
            },
            module: {
                options: {
                    idleading: ''
                },
                files: [
                    {
                        cwd: 'js/module',
                        src: '**',
                        filter: 'isFile',
                        expand: true,
                        dest: 'dist/module'
                    }
                ]
            },
            lib: {
                options: {
                    idleading: 'plugin/'
                },
                files: [{
                    cwd: './plugins',
                    src: getFilePathByAlias(alias, './'),
                    filter: 'isFile',
                    expand: true,
                    dest: 'dist/plugins'
                }]
            }
        },
        concat: {
            module: {
                src: ['dist/module/**'],
                filter: function (filepath) {

                    return /\.js$/i.test(filepath) && !/\-debug\.js$/.test(filepath) && !/mobile\//.test(filepath);
                },
                dest: 'module.js'
            },
            plugin: {
                src: getFilePathByAlias(alias, 'dist/plugins/'),
                filter: function(filepath) {
                    return /\.js$/i.test(filepath) && !/\-debug\.js$/.test(filepath);
                },
                dest: 'plugin.js'
            }
        },
        uglify: {
            module: {
                src: ['module.js'],
                dest: 'module.min.js'
            },
            plugin: {
                src: ['plugin.js'],
                dest: 'plugin.min.js'
            }
        },
        cachebuster: {
            options: {
                format: 'json',
                basedir: '../'
            },
            'hash_info.json': ['module.min.js', 'plugin.min.js']
        }
    });

    grunt.registerTask('default', ['cachebuster']);
}