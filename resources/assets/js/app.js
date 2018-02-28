import Disqus from "./components/Disqus";

// add syntax highlighting like a boss, need for speed (2018)
const Prism = require('prismjs');

const app = new Vue({
    components: {
        'appDisqus': Disqus
    },
    el: '#app'
});
