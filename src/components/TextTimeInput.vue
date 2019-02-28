<template>
  <v-text-field
    :label="label"
    :value="value"
    @input="updateVal($event)"
    append-icon="access_time"
    @blur="sendChange()"
    :error="!valid"
    :rules="[v => !!v || 'Time is required']"
  />
</template>

<script lang="ts">
import { Component, Model, Prop, Vue } from "vue-property-decorator";

@Component({})
export default class TextTimeInput extends Vue {
  @Model("input", { required: true })
  private value!: string;
  @Prop({ required: true })
  private label!: string;

  private internalValue: string = this.value;
  private valid = true;

  sendChange() {
    let val = this.internalValue;
    let error = false;
    val = val.replace(/\s+/g, "");
    val = val.replace(/:/g, "");
    let finalTime = String(val);

    let hourCheck12 = (half: string) => {
      if (val.length === 5) {
        if (
          parseInt(val.substring(0, 1)) < 1 ||
          parseInt(val.substring(1, 3)) > 59
        ) {
          error = true;
        } else {
          finalTime =
            val.substring(0, 1) +
            ":" +
            val.substring(1, 3) +
            " " +
            half.toUpperCase();
        }
      } else if (val.length === 6) {
        if (
          parseInt(val.substring(0, 1)) < 1 ||
          parseInt(val.substring(0, 2)) > 12 ||
          parseInt(val.substring(2, 4)) > 59
        ) {
          error = true;
        } else {
          finalTime =
            val.substring(0, 2) +
            ":" +
            val.substring(2, 4) +
            " " +
            half.toUpperCase();
        }
      } else {
        error = true;
      }
    };

    if (val.substring(val.length - 2, val.length).toLowerCase() === "am") {
      hourCheck12("am");
    } else if (
      val.substring(val.length - 2, val.length).toLowerCase() === "pm"
    ) {
      hourCheck12("pm");
    } else {
      if (val.length === 3) {
        if (parseInt(val.substring(1, 3)) > 59) {
          error = true;
        } else {
          finalTime = val.substring(0, 1) + ":" + val.substring(1, 3) + " AM";
        }
      } else if (val.length === 4) {
        if (parseInt(val.substring(2, 4)) > 59) {
          error = true;
        } else {
          let ampm = parseInt(val.substring(0, 2)) <= 12 ? " AM" : " PM";
          let hour =
            parseInt(val.substring(0, 2)) <= 12
              ? parseInt(val.substring(0, 2))
              : parseInt(val.substring(0, 2)) - 12;
          finalTime = hour + ":" + val.substring(2, 4) + ampm;
        }
      } else {
        error = true;
        finalTime = this.internalValue;
      }
    }

    this.valid = !error;
    this.$emit("input", finalTime);
  }

  updateVal(event: string) {
    this.internalValue = event;
    this.$emit("input", event);
  }
}
</script>

<style scoped></style>
