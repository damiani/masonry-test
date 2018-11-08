<template>
    <div class="container" :style="{ margin: '20px' }">
        <div :style="{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', height: '40px' }">
            <button type="button" @click="add">+ Add Item</button>

            <p>{{ message }}</p>

            <button type="button" @click="addValue">+ Add Value</button>
        </div>

        <hr>

        <kd-masonry
            :columns="{ default: 4, 900: 3, 700: 2, 400: 1 }"
            :spacing="{ default: 20, 900: 10, 700: 5 }"
        >
            <DemoTile
                v-for="(item, index) in items"
                :key="index"
                :value="item + value"
                :index="index"
                @alert="alert"
            ></DemoTile>
        </kd-masonry>
    </div>
</template>

<script>
import DemoTile from './DemoTile.vue';
import KdMasonry from './KdMasonry.vue';

export default {
    components: {
        DemoTile,
        KdMasonry,
    },
    data() {
        return {
            items: Array.from({ length: 8 }, (_, index) => index + 1),
            message: '',
            value: 0,
        };
    },
    methods: {
        add() {
            this.items.push(this.items[this.items.length - 1] + 1);
        },
        addValue() {
            this.value++;
        },
        alert(tile) {
            this.message =
                'Tile ' + tile.index + ' was clicked, value is ' + tile.value;
        },
    },
};
</script>
