<template>
    <div class="blog-list">
        <h1>Posts</h1>
        <article v-for="post in posts" :key="post['@id']" >
            <img :src="post.media"/>
            <div class="post-data">
                <h2>
                    <a v-on:click="dispatchShow" :href="post['@id']">
                        {{  post.title }}
                    </a>
                </h2>
                <p class="post-summary">
                    {{ post.body.substring(0, 700) }}
                </p>
                <p class="post-author">
                    {{ post.author.fullname }}
                </p>
                <p class="post-date">
                    {{ post.created }}
                </p>
            </div>
        </article>
    </div>
    <ul class="pagination">
        <li><a v-on:click="dispatchList" :href="first">First</a></li>
        <li><a v-on:click="dispatchList" :href="next">Next</a></li>
        <li><a v-on:click="dispatchList" :href="last">Last</a></li>
    </ul>
</template>

<script>
    export default {
        name: 'BlogListComponent',
        methods: {
            dispatchList: function(e) {
                e.preventDefault();

                this.$emit('click', 'fetchPosts', e.target.href);
            },
            dispatchShow: function(e) {
                e.preventDefault();

                this.$emit('click', 'fetchPost', e.target.href);
            }
        },
        props: {
            posts: {
                type: Array,
                required: true
            },
            first: {
                type: String,
                required: true
            },
            last: {
                type: String,
                required: true
            },
            next: {
                type: String,
                required: true
            }
        }
    };
</script>