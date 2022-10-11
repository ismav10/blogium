<template>
    <div class="post-inner">
        <article class="post-content">
            <div class="post-header">
                <img class="post-image" :src="post.media" alt="Post image">
            </div>
            <h1 class="post-title">
                {{ post.title }}
            </h1>
            <p class="post-body">
                {{ post.body }}
            </p>
            <div class="post-author">
                <img class="post-author-image" alt="Author's image" src="images/logo.png">
                <div class="post-author-data">
                    <p class="post-author-fullname">{{ post.author.fullname }}</p>
                    <p class="post-date">{{ post.created }}</p>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
    export default {
        name: 'BlogShowComponent',
        props: {
            post: {
                type: Object,
                required: true
            }
        }
    };
</script>