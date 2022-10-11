<template>
    <div v-if="error" class="notification">
        <p>{{ error }}</p>
    </div>
</template>

<script>
    export default {
        name: 'Notification',
        props: {
            error: {
                type: String,
                required: true
            }
        }
    }
</script>