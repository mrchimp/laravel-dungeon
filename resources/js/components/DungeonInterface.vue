<template>
  <div class="tile is-ancestor dungeon">
    <div class="tile is-parent is-3">
      <div class="tile is-child notification is-grey-light dungeon-inventory">
        <h3 class="title is-5">Inventory</h3>
        <ul>
          <li v-for="item in inventory" :key="item.uuid" :title="item.description">
            {{ item.name }}
            <button
              class="button is-default is-small"
              @click.prevent="run('drop ' + item.name)"
            >Drop</button>
            <template v-if="item.type === 'apparel'">
              <button
                class="button is-default is-small"
                @click.prevent="run('equip ' + item.name)"
              >{{ item.equiped ? 'Unequip' : 'Equip' }}</button>
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
            <li v-for="item in items" :key="item.uuid" :title="item.description">
              {{ item.name }}
              <button
                class="button is-default is-small"
                @click.prevent="run('take ' + item.name)"
              >Take</button>
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
              <input
                type="text"
                v-model="input"
                class="input"
                :disabled="sending_input"
                placeholder="Type your commands here..."
              />
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
      input: "look",
      output: [],
      items: [],
      players: [],
      exits: [],
      inventory: [],
      current_room: null,
      room_channel: null
    };
  },

  mounted() {
    this.run("look");

    Echo.private("App.User." + window.user_id).notification(notification => {
      switch (notification.type) {
        case "Dungeon\\Notifications\\WhisperToUser":
          this.output.push(
            `${notification.author_name} whispers: ${notification.message}`
          );
          break;
        default:
          console.error("unknown notification type", notification);
      }
    });
  },

  methods: {
    resetInput() {
      this.input = "";
    },

    submitInput() {
      if (this.input === "clear") {
        this.input = "";
        this.output = "";
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

      console.log("handleRoomChange", new_room_id);

      if (this.current_room) {
        this.joinRoomChannel();
      }
    },

    leaveRoomChannel() {
      console.log("Leaving channel", `Room.${this.current_room}`);
      Echo.leaveChannel(`Room.${this.current_room}`);

      this.room_channel = null;
    },

    joinRoomChannel() {
      console.log("Joining channel", `Room.${this.current_room}`);
      this.room_channel = Echo.private(`Room.${this.current_room}`).listen(
        "UserSaysToRoom",
        e => {
          console.log("UserSaysToRoom", e);
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

      return axios.post("/dungeon/cmd", {
        input: input
      });
    }
  }
};
</script>

<style>
.dungeon {
  width: 100vw;
  height: 100vh;
}
</style>

