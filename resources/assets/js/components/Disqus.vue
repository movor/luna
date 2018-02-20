<template>
    <div id="disqus_thread"></div>
</template>

<script>

    export default {
        props: {
            shortname: {
                type: String,
                required: false,
                default: process.env.MIX_DISQS_WEBSITE
            },
            identifier: {
                type: String,
                required: false,
                default: window.location.pathname
            },
            url: {
                type: String,
                required: false,
                default: document.baseURI
            },
            title: {
                type: String,
                required: false,
                default: process.env.APP_NAME
            }
        },
        mounted() {
            window.DISQUS
                ? this.reset(window.DISQUS)
                : this.init();
        },
        methods: {
            init() {
                const that = this;
                let d = document,
                    s = d.createElement('script');

                window.disqus_config = function () {
                    that.setBaseConfig(this);
                };

                s.setAttribute('id', 'embed-disqus');
                s.setAttribute('data-timestamp', +new Date());
                s.type = 'text/javascript';
                s.async = true;
                s.src = `//${this.shortname}.disqus.com/embed.js`;
                (d.head || d.body).appendChild(s);
            },
            setBaseConfig(disqusConfig) {
                disqusConfig.page.identifier = this.identifier;
                disqusConfig.page.url = this.url;
            },
            reset(disqus) {
                const that = this;
                disqus.reset({
                    reload: true,
                    config: function () {
                        that.setBaseConfig(this)
                    }
                })
            },
        }
    }

</script>