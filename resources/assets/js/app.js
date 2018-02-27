import Disqus from "./components/Disqus";

const app = new Vue({
    components: {
        'appDisqus': Disqus
    },
    el: '#app'
});