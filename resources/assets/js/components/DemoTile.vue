<template>
    <div
        :style="{
            border: '1px solid grey',
            padding: randomHeight + 'px 10px',
            textAlign: 'center'
        }"
        @click="$emit('alert', { index, value })"
    >
        <p>Index: {{ index }}</p>

        <h2>Value: {{ value }}</h2>
    </div>
</template>

<script>
export default {
    props: {
        index: {},
        value: { default: 1 },
    },
    data() {
        return {
            randomHeight: Math.round(Math.random() * (100 - 10) + 10),
        };
    },
};
</script>
