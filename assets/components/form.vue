<template>
    <form class="create-post-form">
        <h1>Create new post</h1>
        <label for="title">Title</label>
        <input type="text" name="title" v-model="post.title">
        <label for="body">Body</label>
        <textarea type="text" rows="50" name="body" v-model="post.body"></textarea>
        <button v-on:click="sendPost" type="submit">Create</button>
    </form>
    <notification :error="error"></notification>
</template>

<script>
    import Notification from './notification';

    export default {
        name: 'BlogFormComponent',
        components: {
            Notification
        },
        data() {
            return {
                post: {},
                error: null
            }
        },
        methods: {
            async sendPost(e) {
                e.preventDefault();
                this.error = null;

                const options = {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(this.post)
                };
                const response = await fetch("/api/blogposts", options);
                const data = await response.json();

                if (data['@type'] === 'ConstraintViolationList') {
                    this.error = data['hydra:description'];

                    return;
                }

                this.post = {};

                window.location = '/secured';
            }
        }
    }
</script>