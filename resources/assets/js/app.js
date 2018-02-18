import Example from "./components/ExampleComponent";

const app = new Vue({
    components: {
        'appExample': Example
    },
    el: '.v-app'
});