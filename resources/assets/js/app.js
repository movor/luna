import Disqus from "./components/Disqus";

// add syntax highlighting like a boss, Need For Speed (2018)
import Prism from 'prismjs';
require('prismjs/components/prism-bash.min');
require('prismjs/components/prism-php.min');
require('prismjs/components/prism-php-extras.min');
require('prismjs/components/prism-javascript.min');
require('prismjs/plugins/toolbar/prism-toolbar.min');
require('prismjs/plugins/show-language/prism-show-language.min');

const app = new Vue({
    components: {
        'appDisqus': Disqus
    },
    el: '#app'
});
