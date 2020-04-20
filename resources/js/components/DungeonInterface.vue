<template>
  <div class="relative">
    <div class="grid grid-rows-maininterface fullheight">
      <div class="bg-red-400 overflow-y-scroll p-1">
        <p v-for="(message, index) in output" :key="index">{{message}}</p>
      </div>

      <div class="flex p-1 bg-blue-400">
        <form @submit.prevent="submitInput" class="flex-1 flex bg-blue-400">
          <input
            class="flex-1 p-1"
            type="text"
            v-model="input"
            :disabled="sending_input"
            placeholder="Type your commands here..."
          />
        </form>
        <p v-if="sending_input" class="flex-1 bg-blue-400">Sending command...</p>
      </div>

      <div class="bg-green-400 grid grid-rows-3 grid-cols-3">
        <div class="flex justify-center items-center"></div>
        <div class="flex justify-center items-center">
          <button type="button" @click.prevent="run('go north')">N</button>
        </div>
        <div class="flex justify-center items-center"></div>

        <div class="flex justify-center items-center">
          <button type="button" @click.prevent="run('go west')">W</button>
        </div>
        <div class="flex justify-center items-center">
          <button type="button" @click.prevent="run('look')">Look</button>
        </div>
        <div class="flex justify-center items-center">
          <button type="button" @click.prevent="run('go east')">E</button>
        </div>

        <div class="flex justify-center items-center"></div>
        <div class="flex justify-center items-center">
          <button type="button" @click.prevent="run('go south')">S</button>
        </div>
        <div class="flex justify-center items-center"></div>
      </div>

      <div class="bg-yellow-400 flex justify-center">
        <button
          type="button"
          class="flex justify-center items-center px-1"
          @click.prevent="show_inventory = !show_inventory"
        >Inv</button>
        <button
          type="button"
          class="flex justify-center items-center px-1"
          @click.prevent="show_players = !show_players"
        >Players</button>
        <button
          type="button"
          class="flex justify-center items-center px-1"
          @click.prevent="show_items = !show_items"
        >Items</button>
      </div>
    </div>

    <div v-if="show_inventory" class="absolute bg-white fullwidth fullheight top-0 left-0">
      <button @click.prevent="show_inventory = false">Close</button>
      <h3>Inventory</h3>
      <ul>
        <li v-for="item in inventory" :key="item.uuid" :title="item.description">
          {{ item.name }}
          <button @click.prevent="run('drop ' + item.name)">Drop</button>
          <template v-if="item.type === 'apparel'">
            <button
              @click.prevent="run('equip ' + item.name)"
            >{{ item.equiped ? 'Unequip' : 'Equip' }}</button>
          </template>
        </li>
      </ul>
    </div>

    <div v-if="show_players" class="absolute bg-white fullwidth fullheight top-0 left-0">
      <button @click.prevent="show_players = false">Close</button>
      <h3>Players</h3>
      <ul>
        <li v-for="player in players" :key="player.uuid">{{ player.name }}</li>
      </ul>
      <h3>NPCs</h3>
      <ul>
        <li v-for="npc in npcs" :key="npc.uuid">{{ npc.name }}</li>
      </ul>
    </div>

    <div v-if="show_items" class="absolute bg-white fullwidth fullheight top-0 left-0">
      <button @click.prevent="show_items = false">Close</button>
      <h3>Items</h3>
      <ul>
        <li v-for="item in items" :key="item.uuid" :title="item.description">
          {{ item.name }}
          <button @click.prevent="run('take ' + item.name)">Take</button>
        </li>
      </ul>
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
      current_room: null,
      room_channel: null,
      show_inventory: false,
      show_players: false,
      show_items: false,
      show_exit: null,
    };
  },

  mounted() {
    this.run('look');

    Echo.private('App.User.' + window.user_id).notification((notification) => {
      switch (notification.type) {
        case 'Dungeon\\Notifications\\WhisperToUser':
          this.output.push(`${notification.author_name} whispers: ${notification.message}`);
          break;
        default:
          console.error('unknown notification type', notification);
      }
    });
  },

  methods: {
    resetInput() {
      this.input = '';
    },

    submitInput() {
      if (this.input === 'clear') {
        this.input = '';
        this.output = '';
        return;
      }

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

      if (response.data.data.room.id !== this.current_room) {
        this.handleRoomChange(response.data.data.room.id);
      }

      this.output.push(response.data.message);

      return response;
    },

    handleRoomChange(new_room_id) {
      if (this.current_room) {
        this.leaveRoomChannel();
      }

      this.current_room = new_room_id;

      console.log('handleRoomChange', new_room_id);

      if (this.current_room) {
        this.joinRoomChannel();
      }
    },

    leaveRoomChannel() {
      console.log('Leaving channel', `Room.${this.current_room}`);
      Echo.leaveChannel(`Room.${this.current_room}`);

      this.room_channel = null;
    },

    joinRoomChannel() {
      console.log('Joining channel', `Room.${this.current_room}`);
      this.room_channel = Echo.private(`Room.${this.current_room}`).listen(
        'UserSaysToRoom',
        (e) => {
          console.log('UserSaysToRoom', e);
          this.output.push(`${e.author_name}: ${e.message}`);
        }
      );

      console.log(this.room_channel);
    },

    run(input) {
      this.runCommand(input)
        .then(this.handleResponse)
        .then(this.resetInput())
        .catch(this.handleError)
        .then(() => {
          this.sending_input = false;
        });
    },

    handleError(error) {
      console.error(error);
      this.output.push(error);
    },

    runCommand(input) {
      this.sending_input = false;

      return axios.post('/dungeon/cmd', {
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

