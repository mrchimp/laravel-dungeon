<template>
    <div class="tile is-ancestor dungeon">
        <div class="tile is-parent is-3">
            <div class="tile is-child notification is-grey-light dungeon-inventory">
                <h3 class="title is-5">Inventory</h3>
                <ul>
                    <li
                        v-for="item in inventory"
                        :key="item.uuid"
                        :title="item.description"
                    >
                        {{ item.name }}
                        <button
                            class="button is-default is-small"
                            @click.prevent="run('drop ' + item.name)"
                        >
                            Drop
                        </button>
                        <template v-if="item.type === 'apparel'">
                            <button
                                class="button is-default is-small"
                                @click.prevent="run('equip ' + item.name)"
                            >
                                {{ item.equiped ? 'Unequip' : 'Equip' }}
                            </button>
                        </template>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tile is-vertical">
            <div class="tile is-parent">
                <div class="tile is-child notification is-grey-light dungeon-people">
                    <h3 class="title is-5">Players</h3>
                    <ul>
                        <li v-for="player in players" :key="player.uuid">{{ player.name }}</li>
                    </ul>
                </div>
                <div class="tile is-child notification is-grey-light dungeon-items">
                    <h3 class="title is-5">Items</h3>
                    <ul>
                        <li
                            v-for="item in items"
                            :key="item.uuid"
                            :title="item.description"
                        >
                            {{ item.name }}
                            <button class="button is-default is-small" @click.prevent="run('take ' + item.name)">Take</button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tile">
                <div class="tile is-parent">
                    <div class="tile is-child notification is-grey-light dungeon-output">
                        <h3 class="title is-5">Output</h3>
                        <p v-for="(message, index) in output" :key="index">{{message}}</p>
                        <form @submit.prevent="submitInput">
                            <input type="text" v-model="input" class="input" :disabled="sending_input" placeholder="Type your commands here...">
                        </form>
                        <p v-if="sending_input">Sending command...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sending_input: false,
            input: 'look',
            output: [],
            items: [],
            players: [],
            exits: [],
            inventory: [],
        };
    },

    mounted() {
        this.run('look');
    },

    methods: {
        resetInput() {
            this.input = '';
        },

        submitInput() {
            this.runCommand(this.input)
                .then(this.handleResponse)
                .then(this.resetInput)
                .catch(this.handleError)
                .then(() => {
                    this.sending_input = false;
                });
        },

        handleResponse(response) {
            if (response.data.success === false) {
                throw response.data.message;
            }

            if (response.data.message) {
                // @todo not sure how to handle new lines in output...
                // Might be best to split response into an array of lines.
                // This would allow adding styling and stuff too...
                // response.data.message.replace(/\n/g, '<br>');
            } else {
                response.data.message = 'No Response';
            }

            if (response.data.data.items) {
                this.items = response.data.data.items;
            }

            if (response.data.data.exits) {
                this.exits = response.data.data.exits;
            }

            if (response.data.data.players) {
                this.players = response.data.data.players;
            }

            if (response.data.data.inventory) {
                this.inventory = response.data.data.inventory;
            }

            this.output.push(response.data.message);

            return response;
        },

        run(input) {
            this.runCommand(input)
                .then(this.handleResponse)
                .then(this.resetInput())
                .catch(this.handleError)
                .then(() => {
                    this.sending_input = false;
                })
        },

        handleError(error) {
            console.error(error);
            this.output.push(error);
        },

        runCommand(input) {
            this.sending_input = false;

            return axios
                .post('cmd', {
                    input: input,
                });
        },
    },
};
</script>

<style>
    .dungeon {
        width: 100vw;
        height: 100vh;
    }
</style>

