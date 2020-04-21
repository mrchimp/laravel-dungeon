<template>
  <div class="relative">
    <div class="grid grid-rows-maininterface h-screen">
      <div class="overflow-y-scroll p-1 leading-tight" ref="output">
        <template v-for="(message, index) in output">
          <input-message v-if="message.type === 'input'" :message="message" :key="index" />
          <output-message v-if="message.type === 'output'" :message="message" :key="index" />
          <error-message v-if="message.type === 'error'" :message="message" :key="index" />
          <chat-message v-if="message.type === 'chat'" :message="message" :key="index" />
        </template>
      </div>

      <div class="flex p-1">
        <form @submit.prevent="submitInput" class="flex-1 flex">
          <input
            class="flex-1 px-2 py-1 text-lg rounded border border-gray-200"
            type="text"
            v-model="input"
            :disabled="sending_input"
            placeholder="Type your commands here..."
            @keyup.up="prevCmd"
            @keyup.down="nextCmd"
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

      <div v-else class="flex justify-center">
        <div class="grid grid-rows-3 grid-cols-3 w-full max-w-screen-sm">
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              class="btn btn-grid"
              @click.prevent="show_inventory = !show_inventory"
            >Inv</button>
          </div>
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'north'"
              class="btn btn-grid"
              :class="{'btn-faded': exits.north === null}"
            >N</button>
          </div>
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              class="btn btn-grid"
              @click.prevent="show_players = !show_players"
            >Ply ({{ players.length + npcs.length }})</button>
          </div>

          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'west'"
              class="btn btn-grid"
              :class="{'btn-faded': exits.west === null}"
            >W</button>
          </div>
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              @click.prevent="quickRun('look')"
              class="btn btn-grid"
            >Look</button>
          </div>
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'east'"
              class="btn btn-grid"
              :class="{'btn-faded': exits.east === null}"
            >E</button>
          </div>

          <div class="flex justify-center items-center py-1"></div>
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              @click.prevent="preview_exit_direction = 'south'"
              class="btn btn-grid"
              :class="{'btn-faded': exits.south === null}"
            >S</button>
          </div>
          <div class="flex justify-center items-center py-1">
            <button
              type="button"
              class="btn btn-grid"
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
      <p v-if="players.length === 0">
        There are no players here.
      </p>
      <ul v-else>
        <li v-for="player in players" :key="player.uuid">{{ player.name }}</li>
      </ul>
      <h3 class="font-bold text-lg mt-4">NPCs</h3>
      <p v-if="npcs.length === 0">
        There are no NPCs here.
      </p>
      <ul v-else>
        <li v-for="npc in npcs" :key="npc.uuid">{{ npc.name }}</li>
      </ul>
    </div>

    <div v-if="show_items" class="modal">
      <button @click.prevent="show_items = false" class="btn absolute right-0 top-0 m-1">Close</button>
      <h3 class="font-bold text-lg">Items</h3>
      <ul>
        <li v-for="item in items" :key="item.uuid" :title="item.description" class="mb-2">
          <div>
            {{ item.name }}
            <button
              class="btn"
              @click="show_item_description = !!show_item_description ? null : item.uuid"
            >?</button>
            <button class="btn" :class="{'btn-faded': !item.can_be_taken}" @click.prevent="take(item)">Take</button>
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
import ChatMessage from './ChatMessage.vue';
import CmdStack from '../CmdStack';

export default {
  components: {
    ErrorMessage,
    InputMessage,
    OutputMessage,
    ChatMessage,
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
      cmd_stack: new CmdStack('history', 100),
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
          this.addOutput({
            type: 'chat',
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
      this.cmd_stack.push(this.input);
      this.cmd_stack.reset();
      this.input = '';
    },

    submitInput() {
      if (this.input === 'clear') {
        this.resetInput();
        this.output = [];
        return;
      }

      this.run(this.input);
    },

    handleResponse(response) {
      if (response.success === false) {
        throw response.message;
      }

      if (response.message) {
        this.addOutput({
          type: 'output',
          text: response.message,
        });
      }

      this.items = get(response, 'data.environment.items', []);
      this.exits = get(response, 'data.environment.exits', []);
      this.players = get(response, 'data.environment.players', []);
      this.inventory = get(response, 'data.environment.inventory', []);
      this.npcs = get(response, 'data.environment.npcs', []);

      if (get(response, 'data.environment.room.uuid') !== get(this, 'current_room.uuid')) {
        this.handleRoomChange(get(response, 'data.environment.room'));
      }

      return response;
    },

    handleRoomChange(new_room) {
      if (this.current_room) {
        this.leaveRoomChannel();
      }

      this.current_room = new_room;

      if (this.current_room) {
        this.joinRoomChannel();
      }
    },

    leaveRoomChannel() {
      Echo.leaveChannel(`Room.${this.current_room.uuid}`);

      this.room_channel = null;
    },

    joinRoomChannel() {
      this.room_channel = Echo.private(`Room.${this.current_room.uuid}`).listen(
        'UserSaysToRoom',
        (e) => {
          this.addOutput({
            type: 'chat',
            name: e.author_name,
            text: e.message,
          });
        }
      );
    },

    quickRun(input) {
      this.input = input;
      this.run(this.input);
    },

    run(input) {
      this.addOutput({
        type: 'input',
        text: this.input,
      });
      this.resetInput();

      this.getResponse(input)
        .then(this.handleResponse)
        .catch(this.handleError)
        .then(() => {
          this.sending_input = false;
        });
    },

    handleError(error) {
      console.error(error);
      this.addOutput({
        type: 'error',
        text: error,
      });
    },

    addOutput(message) {
      this.output.push(message);
      this.$nextTick(() => {
        this.$refs.output.scrollTop = this.$refs.output.scrollHeight;
      });
    },

    async getResponse(input) {
      this.sending_input = false;

      try {
        const data = JSON.stringify({
          input: input,
        });
      } catch (e) {
        console.error('fail?', e);
      }

      const response = await fetch('/dungeon/cmd', {
        method: 'POST',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
          input: input,
        }),
      });

      return response.json();
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

    take(item) {
      this.quickRun('take ' + item.name);

      if (this.items.length === 0) {
        this.show_items = false;
      }
    },

    prevCmd() {
      this.input = this.cmd_stack.prev();
    },

    nextCmd() {
      this.input = this.cmd_stack.next();
    },
  },
};
</script>
