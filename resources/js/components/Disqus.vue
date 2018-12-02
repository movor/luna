<template>
    <div id="disqus_thread"></div>
</template>

<script>

    export default {
        props: {
            website: {
                required: true,
            },
            // https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables#thispagetitle
            title: {
                required: true,
            },
            // https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables#thispageidentifier
            identifier: {
                required: true,
            },
            // https://help.disqus.com/customer/portal/articles/472098-javascript-configuration-variables#thispageurl
            url: {
                required: true,
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
                s.src = `//${this.website}.disqus.com/embed.js`;
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