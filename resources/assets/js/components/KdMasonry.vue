<script>
import resize from 'vue-resize-directive';

export default {
    directives: { resize },
    props: {
        columns: { default: 3 },
        spacing: { default: 10 },
    },
    data() {
        return {
            current: {
                columns: null,
                spacing: null,
                width: null,
            },
            ordered: {
                columns: this.orderBreakpoints(this.columns),
                spacing: this.orderBreakpoints(this.spacing),
            },
        };
    },
    created() {
        this.updateColumns();
    },
    methods: {
        getValueForBreakpoint(orderedProp, width) {
            if (parseInt(orderedProp) > -1) {
                return orderedProp;
            }

            return orderedProp.find(breakpoint => breakpoint.maxWidth >= width)
                .value;
        },
        orderBreakpoints(prop) {
            let propAsNumber = parseInt(prop);

            if (propAsNumber > -1) {
                return propAsNumber;
            }

            return Object.keys(prop)
                .sort()
                .map(breakpoint => {
                    return {
                        maxWidth: parseInt(breakpoint) || Infinity,
                        value: prop[breakpoint],
                    };
                });
        },
        updateColumns() {
            if (window.innerWidth != this.current.width) {
                this.current.width = window.innerWidth;
                this.current.columns = this.getValueForBreakpoint(
                    this.ordered.columns,
                    this.current.width
                );
                this.current.spacing = this.getValueForBreakpoint(
                    this.ordered.spacing,
                    this.current.width
                );
            }
        },
    },
    created() {
        this.updateColumns();
    },
    render(h) {
        let columns = Array.from(Array(this.current.columns)).map(column => []);
        let tiles = this.$slots.default.filter(tile => tile.tag);
        let styles = {
            column: {
                flex: 1,
                margin: this.current.spacing / 2 + 'px',
                width: 100 / this.columns + '%',
            },
            container: {
                display: ['-webkit-box', '-ms-flexbox', 'flex'],
                margin: '0 -' + this.current.spacing / 2 + 'px',
            },
            spaceBelow: {
                height: this.current.spacing + 'px',
            },
        };

        for (var i = 0; i < tiles.length; i++) {
            let column = columns[i % this.current.columns];
            column.push(tiles[i]);
            column.push(h('div', { style: styles.spaceBelow }));
        }

        return h(
            'div',
            {
                style: styles.container,
                directives: [
                    {
                        name: 'resize',
                        value: this.updateColumns,
                        arg: 'throttle',
                    },
                ],
            },
            columns.map(column => h('div', { style: styles.column }, column))
        );
    },
};
</script>
