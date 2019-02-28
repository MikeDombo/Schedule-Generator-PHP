<template>
  <v-card class="mb-5">
    <v-card-title>
      <v-container fluid>
        <v-layout row wrap>
          <v-flex sm12 md11>
            <v-layout row wrap>
              <v-text-field
                label="Name"
                placeholder="Enter a name"
                :value="course['Course Name']"
                required
                @input="
                  $emit('update:course', { ...course, 'Course Name': $event })
                "
                class="pr-2"
                :rules="[v => !!v || 'Name is required']"
              />
              <v-text-field
                label="Field of Study"
                placeholder="ex. CMSC"
                :value="course['Field of Study']"
                required
                @input="
                  $emit('update:course', {
                    ...course,
                    'Field of Study': $event
                  })
                "
                class="pr-2"
              />
              <v-text-field
                label="Course Number"
                placeholder="ex. 101"
                :value="course['course number']"
                required
                @input="
                  $emit('update:course', {
                    ...course,
                    'course number': $event
                  })
                "
                class="pr-2"
              />
              <v-text-field
                label="Number of Units"
                placeholder="ex. 1"
                :value="course.Units"
                required
                @input="$emit('update:course', { ...course, Units: $event })"
                :rules="[v => (v && parseFloat(v) >= 0) || 'Must be a number']"
              />
            </v-layout>
          </v-flex>
          <v-flex md1 xs12>
            <v-layout row wrap class="inline">
              <v-btn color="success" block @click="$emit('add')">
                <v-icon>add</v-icon>
              </v-btn>
              <v-btn color="error" block @click="$emit('remove')">
                <v-icon>remove</v-icon>
              </v-btn>
            </v-layout>
          </v-flex>
        </v-layout>
        <v-divider />
      </v-container>
    </v-card-title>
    <v-card-text>
      <v-layout row wrap>
        <template v-for="(section, index) in course.sections">
          <v-flex lg4 :key="index">
            <v-card class="mx-3 mb-3">
              <v-card-title>
                <v-layout row wrap>
                  <v-flex xs10>
                    <v-text-field
                      label="CRN"
                      placeholder="Unique Section ID"
                      :value="section.crn"
                      @input="updateSection($event, index, 'crn')"
                      required
                      :rules="[v => !!v || 'CRN is required']"
                    />
                  </v-flex>
                  <v-flex xs2>
                    <v-layout row wrap class="inline">
                      <v-btn color="success" block @click="add(index)">
                        <v-icon>add</v-icon>
                      </v-btn>
                      <v-btn color="error" block @click="remove(index)">
                        <v-icon>remove</v-icon>
                      </v-btn>
                    </v-layout>
                  </v-flex>
                  <v-flex xs12>
                    <v-divider />
                  </v-flex>
                </v-layout>
              </v-card-title>
              <v-card-text>
                <template v-for="(time, timeIndex) in section.times">
                  <div :key="index + timeIndex">
                    <v-layout row wrap>
                      <v-flex xs10>
                        <v-select
                          label="Day of Week"
                          :items="daysOfWeek"
                          :value="time.day"
                          @input="updateTime($event, index, timeIndex, 'day')"
                          required
                          :rules="[v => !!v || 'Day of week is required']"
                        />
                      </v-flex>
                      <v-flex xs2>
                        <v-layout row wrap class="inline">
                          <v-btn
                            color="success"
                            @click="addTime(index, timeIndex)"
                            block
                          >
                            <v-icon>add</v-icon>
                          </v-btn>
                          <v-btn
                            color="error"
                            block
                            @click="removeTime(index, timeIndex)"
                          >
                            <v-icon>remove</v-icon>
                          </v-btn>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    <v-layout row>
                      <TextTimeInput
                        label="From"
                        class="pr-1"
                        :value="time.from"
                        @input="updateTime($event, index, timeIndex, 'from')"
                      />
                      <TextTimeInput
                        label="To"
                        :value="time.to"
                        @input="updateTime($event, index, timeIndex, 'to')"
                      />
                    </v-layout>
                  </div>
                </template>
              </v-card-text>
            </v-card>
          </v-flex>
        </template>
      </v-layout>
    </v-card-text>
  </v-card>
</template>

<script lang="ts">
import { Component, Model, Vue } from "vue-property-decorator";
import TextTimeInput from "@/components/TextTimeInput.vue";

@Component({
  components: { TextTimeInput }
})
export default class CourseInput extends Vue {
  @Model("update:course", { required: true })
  private course!: any;

  private daysOfWeek = [
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday"
  ];

  updateSection(event: any, index: number, field: string) {
    const sections = this.course.sections;
    sections[index] = { ...sections[index], [field]: event };
    this.$emit("update:course", { ...this.course, sections });
  }

  updateTime(
    event: any,
    sectionIndex: number,
    timeIndex: number,
    field: string
  ) {
    const sections = [...this.course.sections];
    sections[sectionIndex].times[timeIndex][field] = event;
    this.$emit("update:course", { ...this.course, sections });
  }

  addTime(sectionIndex: number, timeIndex: number) {
    const sections = this.course.sections;
    sections[sectionIndex].times.splice(timeIndex + 1, 0, {});
    this.$emit("update:course", { ...this.course, sections });
  }

  removeTime(sectionIndex: number, timeIndex: number) {
    const sections = this.course.sections;
    if (sections[sectionIndex].times.length > 1) {
      sections[sectionIndex].times.splice(timeIndex, 1);
    }
    this.$emit("update:course", { ...this.course, sections });
  }

  add(index: number) {
    const sections = this.course.sections;
    sections.splice(index + 1, 0, { times: [{}] });
    this.$emit("update:course", { ...this.course, sections });
  }

  remove(index: number) {
    const sections = this.course.sections;
    if (sections.length > 1) {
      sections.splice(index, 1);
    }
    this.$emit("update:course", { ...this.course, sections });
  }
}
</script>

<style scoped lang="scss">
.btn-inline {
}
.inline {
  .v-btn {
    padding: 0;
    margin: 2px;
    min-width: 16px;
    max-width: 40px;
  }

  justify-content: flex-end;
  margin-bottom: 1em;
}
</style>
