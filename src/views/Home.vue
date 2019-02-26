<template>
  <div>
    <v-layout row wrap>
      <v-flex xs12>
        <h1 class="font-weight-light display-1">Make A Schedule</h1>
      </v-flex>
      <CoursesInput :model="model" @update:model="model = $event" />
    </v-layout>
    <v-layout>
      <v-flex xs12 sm8 md4 offset-sm2 offset-md4>
        <v-btn block color="success" @click="submit">Submit</v-btn>
      </v-flex>
    </v-layout>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import CoursesInput from "../components/CouresesInput.vue";

@Component({
  components: {
    CoursesInput
  }
})
export default class Home extends Vue {
  private model = [
    { sections: [{ times: [{ day: null, from: "", to: "" }] }] }
  ];

  created() {
    const oldSearch = new URLSearchParams(window.location.search).get("i");
    try {
      if (oldSearch) {
        const data = JSON.parse(oldSearch);
        if (Array.isArray(data)) {
          data.forEach((course: { sections: any[] }) => {
            course.sections.forEach(section => {
              if (!("times" in section)) {
                section.times = [];
              }
              Object.keys(section).forEach((key: string) => {
                if (Number.isInteger(parseFloat(key))) {
                  section.times[key] = section[key];
                  delete section[key];
                }
              });
            });
          });
          this.model = data;
        }
      }
    } catch {
      // empty-block
    }
  }

  submit() {
    const modelTransfer = this.model;
    modelTransfer.forEach((course: { sections: { times: any[] }[] }) => {
      course.sections.forEach(section => {
        section.times.forEach((time, index) => {
          //@ts-ignore
          section[index] = time;
        });
        delete section.times;
      });
    });

    window.location.assign(
      "makeSchedule.php?i=" + encodeURIComponent(JSON.stringify(modelTransfer))
    );
  }
}
</script>
