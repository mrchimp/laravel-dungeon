<template>
  <div class="relative">
    <div class="grid grid-rows-maininterface h-screen">
      <div class="overflow-y-scroll p-1 leading-tight">
        <template v-for="(message, index) in output">
          <input-message v-if="message.type==='input'" :message="message" :key="index" />
          <output-message v-if="message.type === 'output'" :message="message" :key="index" />
          <error-message v-if="message.type === 'error'" :message="message" :key="index" />
          <whisper-message v-if="message.type === 'whisper'" :message="message" :key="index" />
        </template>
      </div>

      <div class="flex px-2 py-1 bg-gray-200">
        <form @submit.prevent="submitInput" class="flex-1 flex">
          <input
            class="flex-1 p-1 text-lg"
            type="text"
            v-model="input"
            :disabled="sending_input"
            placeholder="Type your commands here..."
          />
        </form>
        <p v-if="sending_input" class="flex-1 bg-gray-400 text-gray-600 text-lg">Sending command...</p>
      </div>

      <div class="flex justify-center items-center">
        <div class="grid grid-rows-3 grid-cols-3 w-full max-w-screen-sm">
          <div class="flex justify-center items-center">
            <button
              type="button"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
              @click.prevent="show_inventory = !show_inventory"
            >Inv</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="quickRun('go north')"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
            >N</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
              @click.prevent="show_players = !show_players"
            >Ply ({{ players.length + npcs.length }})</button>
          </div>

          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="quickRun('go west')"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
            >W</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="quickRun('look')"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
            >Look</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="quickRun('go east')"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
            >E</button>
          </div>

          <div class="flex justify-center items-center"></div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="quickRun('go south')"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
            >S</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              class="text-xl font-bold bg-gray-200 rounded p-2 m-2 w-full"
              @click.prevent="show_items = !show_items"
            >Itm ({{ items.length }})</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="show_inventory" class="absolute bg-white w-screen h-screen top-0 left-0">
      <button @click.prevent="show_inventory = false">Close</button>
      <h3>Inventory</h3>
      <ul>
        <li v-for="item in inventory" :key="item.uuid" :title="item.description">
          {{ item.name }}
          <button @click.prevent="quickRun('drop ' + item.name)">Drop</button>
          <template v-if="item.type === 'apparel'">
            <button
              @click.prevent="quickRun('equip ' + item.name)"
            >{{ item.equiped ? 'Unequip' : 'Equip' }}</button>
          </template>
        </li>
      </ul>
    </div>

    <div v-if="show_players" class="absolute bg-white w-screen h-screen top-0 left-0">
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
          <button @click.prevent="quickRun('take ' + item.name)">Take</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import get from 'lodash/get';
import ErrorMessage from './ErrorMessage.vue';
import InputMessage from './InputMessage.vue';
import OutputMessage from './OutputMessage.vue';
import WhisperMessage from './WhisperMessage.vue';

export default {
  components: {
    ErrorMessage,
    InputMessage,
    OutputMessage,
    WhisperMessage,
  },

  data() {
    return {
      sending_input: false,
      input: 'look',
      output: [],
      items: [],
      players: [],
      npcs: [],
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
    this.quickRun('look');

    Echo.private('App.User.' + window.user_id).notification((notification) => {
      switch (notification.type) {
        case 'Dungeon\\Notifications\\WhisperToUser':
          this.output.push({
            type: 'whisper',
            name: notification.author_name,
            text: `"${notification.message}"`,
          });
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
        this.output = [];
        return;
      }

      this.run(this.input);
    },

    handleResponse(response) {
      if (response.data.success === false) {
        throw response.data.message;
      }

      this.output.push({
        type: 'input',
        text: this.input,
      });

      if (response.data.message) {
        this.output.push({
          type: 'output',
          text: response.data.message,
        });
      }

      this.items = get(response, 'data.data.room.items', []);
      this.exits = get(response, 'data.data.room.exits', []);
      this.players = get(response, 'data.data.room.players', []);
      this.inventory = get(response, 'data.data.room.inventory', []);
      this.npcs = get(response, 'data.data.room.npcs', []);

      if (response.data.data.room.id !== this.current_room) {
        this.handleRoomChange(response.data.data.room.id);
      }

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

    quickRun(input) {
      this.input = input;
      this.run(this.input);
    },

    run(input) {
      this.getResponse(input)
        .then(this.handleResponse)
        .then(this.resetInput)
        .catch(this.handleError)
        .then(() => {
          this.sending_input = false;
        });
    },

    handleError(error) {
      console.error(error);
      this.output.push({
        type: 'error',
        text: error,
      });
    },

    getResponse(input) {
      this.sending_input = false;

      return axios.post('/dungeon/cmd', {
        input: input,
      });
    },
  },
};
</script>
