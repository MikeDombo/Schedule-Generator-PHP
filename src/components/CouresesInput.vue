<template>
  <v-layout row wrap>
    <v-flex xs12 v-for="(course, index) in model" :key="index">
      <CourseInput
        :course="course"
        @update:course="doUpdateExisting($event, index)"
        @add="add(index)"
        @remove="remove(index)"
      />
    </v-flex>
  </v-layout>
</template>

<script lang="ts">
import { Component, Model, Vue } from "vue-property-decorator";
import CourseInput from "@/components/CourseInput.vue";

@Component({
  components: { CourseInput }
})
export default class CoursesInput extends Vue {
  @Model("update:model", { required: true })
  private model!: any[];

  doUpdateExisting(data: {}, index: number) {
    const models = [...this.model];
    models[index] = data;
    this.$emit("update:model", models);
  }

  add(index: number) {
    const model = this.model;
    model.splice(index + 1, 0, { sections: [{ times: [{}] }] });
    this.$emit("update:model", model);
  }

  remove(index: number) {
    const model = this.model;
    if (model.length > 1) {
      model.splice(index, 1);
    }
    this.$emit("update:model", model);
  }
}
</script>

<style></style>
