require('./bootstrap');

window.CodeMirror = require('codemirror')
window.CodeFlask = require('codeflask/build/codeflask.min')
require('codemirror/mode/htmlmixed/htmlmixed')
require('@toast-ui/jquery-editor');
require('highlight.js/styles/github.css')
window.codeSyntaxHighlight = require('@toast-ui/editor-plugin-code-syntax-highlight')

