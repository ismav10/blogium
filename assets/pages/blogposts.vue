<template>
    <nav>
        <ul class="navigation">
            <li>
                <a href="#" v-on:click="fetchPosts()" class="link-button-gray">
                    Blogs
                </a>
            </li>
            <li>
                <a href="#" v-on:click="showForm()" class="link-button">
                    Create
                </a>
            </li>
        </ul>
    </nav>
    <blog-list-component v-if="state === 'list'" :posts="posts" :first="first" :last="last" :next="next" @click="handleEvent"></blog-list-component>
    <blog-show-component v-if="state === 'show'" :post="post" @click="handleEvent"></blog-show-component>
    <blog-form-component v-if="state === 'form'" :post="post" @click="handleEvent"></blog-form-component>
</template>

<script>
    import BlogListComponent from '../components/list';
    import BlogShowComponent from '../components/show';
    import BlogFormComponent from '../components/form';

    export default {
        name: 'BlogPosts',
        components: {
            BlogListComponent,
            BlogShowComponent,
            BlogFormComponent
        },
        data() {
            return {
                post: {},
                posts: [],
                first: null, 
                last: null,
                next: null,
                state: 'list'
            }
        },
        methods: {
            fetchPosts: async function(url) {
                let response = url ?
                    await fetch(url) : 
                    await fetch('/api/blogposts');
                let data = await response.json();

                this.posts = data['hydra:member'];
                this.first = data['hydra:view']['hydra:first'];
                this.last = data['hydra:view']['hydra:last'];
                this.next = data['hydra:view']['hydra:next'];

                this.state = 'list';
            },
            fetchPost: async function(url) {
                let response = await fetch(url);
                let data = await response.json();

                this.post = data;
                this.state = 'show';
            },
            showForm: async function() {
                this.state = 'form';
            },
            handleEvent: function(method, url) {
                if (method === 'fetchPosts') {
                    this.fetchPosts(url);
                } else {
                    this.fetchPost(url);
                }
            }
        },
        async mounted() {
            let response = await fetch('/api/blogposts');
            let data = await response.json();

            this.posts = data['hydra:member'];
            this.first = data['hydra:view']['hydra:first'];
            this.last = data['hydra:view']['hydra:last'];
            this.next = data['hydra:view']['hydra:next'];
        }
};
</script>