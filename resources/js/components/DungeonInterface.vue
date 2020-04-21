<template>
  <div class="relative">
    <div class="grid grid-rows-maininterface h-screen">
      <div class="overflow-y-scroll p-1 leading-tight">
        <template v-for="(message, index) in output">
          <input-message v-if="message.type === 'input'" :message="message" :key="index" />
          <output-message v-if="message.type === 'output'" :message="message" :key="index" />
          <error-message v-if="message.type === 'error'" :message="message" :key="index" />
          <whisper-message v-if="message.type === 'whisper'" :message="message" :key="index" />
        </template>
      </div>

      <div class="flex p-2 bg-gray-200">
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

      <div v-if="preview_exit_direction !== null" class="p-2 relative">
        <button @click.prevent="preview_exit_direction = null" class="btn float-right m-1">Close</button>

        <p v-if="preview_exit === null">You can't go that way.</p>
        <div v-else>
          <h4 class="font-bold pr-4">{{ preview_exit.name }}</h4>
          <p class="leading-none pr-4 mb-2">{{ preview_exit.description }}</p>
          <div v-if="preview_exit.locked">
            <p>The door is locked.</p>
            <div class="grid grid-rows-2">
              <div class="flex my-1">
                <input
                  class="border border-gray-200 rounded p-2 rounded-r-none flex-1 min-w-0"
                  type="text"
                  name="code"
                  v-model="door_code"
                  placeholder="Code"
                />
                <button
                  class="btn mr-2 flex-auto rounded-l-none"
                  @click.prevent="unlockDoorWithCode"
                >Use code</button>
                <button @click.prevent="unlockDoorWithKey" class="btn flex-auto">Use key</button>
              </div>
            </div>
          </div>
          <p v-else>
            <p>The door is unlocked.</p>
            <button
              @click.prevent="
                quickRun(`go ${preview_exit_direction}`);
                preview_exit_direction = null;
              "
              class="btn"
            >Go</button>
          </p>
        </div>
      </div>

      <div v-else class="flex justify-center items-center">
        <div class="grid grid-rows-3 grid-cols-3 w-full max-w-screen-sm">
          <div class="flex justify-center items-center">
            <button
              type="button"
              class="btn text-xl font-bold m-2 w-full"
              @click.prevent="show_inventory = !show_inventory"
            >Inv</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'north'"
              class="btn text-xl font-bold m-2 w-full"
            >N</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              class="btn text-xl font-bold m-2 w-full"
              @click.prevent="show_players = !show_players"
            >Ply ({{ players.length + npcs.length }})</button>
          </div>

          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'west'"
              class="btn text-xl font-bold m-2 w-full"
            >W</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="quickRun('look')"
              class="btn text-xl font-bold m-2 w-full"
            >Look</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'east'"
              class="btn text-xl font-bold m-2 w-full"
            >E</button>
          </div>

          <div class="flex justify-center items-center"></div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'south'"
              class="btn text-xl font-bold m-2 w-full"
            >S</button>
          </div>
          <div class="flex justify-center items-center">
            <button
              type="button"
              class="btn text-xl font-bold m-2 w-full"
              @click.prevent="show_items = !show_items"
            >Itm ({{ items.length }})</button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="show_inventory" class="modal">
      <button @click.prevent="show_inventory = false" class="btn absolute right-0 top-0 m-1">Close</button>
      <h3 class="font-bold text-lg">Inventory</h3>
      <ul>
        <li v-for="item in inventory" :key="item.uuid" :title="item.description">
          <div>
            {{ item.name }}
            <button
              class="btn"
              @click="show_inventory_description = !!show_inventory_description ? null : item.uuid"
            >?</button>
            <button class="btn" @click.prevent="quickRun('drop ' + item.name)">Drop</button>
            <template v-if="item.type === 'apparel'">
              <button
                class="btn"
                @click.prevent="quickRun('equip ' + item.name)"
              >{{ item.equiped ? 'Unequip' : 'Equip' }}</button>
            </template>
          </div>
          <div v-if="show_inventory_description === item.uuid">{{ item.description }}</div>
        </li>
      </ul>
    </div>

    <div v-if="show_players" class="modal">
      <button @click.prevent="show_players = false" class="btn absolute right-0 top-0 m-1">Close</button>
      <h3 class="font-bold text-lg">Players</h3>
      <ul>
        <li v-for="player in players" :key="player.uuid">{{ player.name }}</li>
      </ul>
      <h3 class="font-bold text-lg mt-4">NPCs</h3>
      <ul>
        <li v-for="npc in npcs" :key="npc.uuid">{{ npc.name }}</li>
      </ul>
    </div>

    <div v-if="show_items" class="modal">
      <button @click.prevent="show_items = false" class="btn absolute right-0 top-0 m-1">Close</button>
      <h3 class="font-bold text-lg">Items</h3>
      <ul>
        <li v-for="item in items" :key="item.uuid" :title="item.description">
          <div>
            {{ item.name }}
            <button
              class="btn"
              @click="show_item_description = !!show_item_description ? null : item.uuid"
            >?</button>
            <button class="btn" @click.prevent="quickRun('take ' + item.name)">Take</button>
          </div>
          <div v-if="show_item_description === item.uuid">{{ item.description }}</div>
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
      preview_exit_direction: null,
      show_item_description: null,
      show_inventory_description: null,
      door_code: '',
    };
  },

  computed: {
    preview_exit() {
      return get(this.exits, this.preview_exit_direction, null);
    },
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

    unlockDoorWithCode() {
      this.quickRun(`unlock ${this.preview_exit_direction} door with code ${this.door_code}`);
      this.preview_exit_direction = null;
      this.door_code = '';
    },

    unlockDoorWithKey() {
      this.quickRun(`unlock ${this.preview_exit_direction} door`);
      this.preview_exit_direction = null;
    },
  },
};
</script>
