<template>
    <div class="tile is-ancestor dungeon">
        <div class="tile is-parent is-3">
            <div class="tile is-child dungeon-inventory">
                Inventory
            </div>
        </div>
        <div class="tile is-vertical">
            <div class="tile is-parent">
                <div class="tile is-child dungeon-people">
                    People
                </div>
                <div class="tile is-child dungeon-items">
                    Items
                </div>
            </div>
            <div class="tile is-parent">
                <div class="tile is-child dungeon-output">
                    Output
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style>
    .dungeon {
        width: 100%;
        height: 500px;
    }
</style>

