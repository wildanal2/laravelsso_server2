import './bootstrap';
import hljs from 'highlight.js';
import Swal from 'sweetalert2/dist/sweetalert2.js';
import '@material-tailwind/html/scripts/ripple';

window.hljs = hljs;
window.Swal = Swal;

if (typeof oldIE === 'undefined' && Object.keys && typeof hljs !== 'undefined') {
    hljs.highlightAll();
}
