import './bootstrap';
import hljs from 'highlight.js';

window.hljs = hljs;
 
if (typeof oldIE === 'undefined' && Object.keys && typeof hljs !== 'undefined') {
    hljs.highlightAll();
}
