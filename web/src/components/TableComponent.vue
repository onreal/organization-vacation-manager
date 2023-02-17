<template>
  <div class="table-container" role="table" aria-label="Applications">
    <div class="flex-table header" role="rowgroup">
      <div
          v-for="(head, index) in configure"
          :key="index"
          :class="index === 0 ? 'flex-row first' : 'flex-row'" role="columnheader">
        {{head.title}}</div>
    </div>
    <div v-for="(item, index) in data" :key="index" class="flex-table row" @click="colNavigation(item)" role="rowgroup">
      <div v-for="(col, num) in configure" :key="num" :class="num === 0 ? 'flex-row first' : 'flex-row'" role="cell">
        <span v-if="num === 0" class="flag-icon flag-icon-gb"></span>
        {{ colValue(num, item) }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TableComponent",
  props: {
    /**
     * The configuration object
     * [{
     *    title: 'First Name', // String: header title for the row
     *    property: 'firstName' // Array|String: property to get value for the row columns
     * }]
     * @Array configure
     */
    configure: Array,
    /**
     * Feed table with data, data object properties should mach configuration properties
     * [{}]
     * @Array configure
     */
    data: Array,
    /**
     * Column navigation settings
     * {
     *    path: '/user/:userId:', ## parameters should enclosed with :
     *    params: []
     * }
     * @Array configure
     */
    navigator: Object
  },
  methods: {
    /**
     * If a configuration property contains an array of properties, then we have to merge them under one column.
     * e.g. Period row should have as value 22/03/2023 - 29/03/2023
     *
     * @param {number} i
     * @param {*} item
     */
    colValue(i, item) {
      if (Array.isArray(this.configure[i].property)) {
        let response = '';
        for (let k = 0; this.configure[i].property.length > k; k++) {
          if (k !== 0) {
            response += ' - '
          }
          response += item[this.configure[i].property[k]]
        }

        return response;
      }
      return item[this.configure[i].property]
    },
    colNavigation(goto) {
      if (!this.navigator) {
        return
      }
      if (!this.navigator.hasOwnProperty('path') || !this.navigator.path || this.navigator.path === '') {
        return
      }
      if (!this.navigator.hasOwnProperty('params') || !this.navigator.params) {
        this.$router.push({ path: this.navigator.path });
        return
      }
      // if (!(/[0-9]/.test(this.navigator.path))) {
      //   this.$router.push({ path: this.navigator.path });
      // }

      let path = null
      let pathArr = this.navigator.path.split(':')
      for (let i =0; pathArr.length > i; i++) {
        if (i === 0) {
          path = pathArr[i]
          continue;
        }
        if (goto.hasOwnProperty(pathArr[i])) {
          path += goto[pathArr[i]]
        } else if (pathArr[i]) {
          path += pathArr[i]
        }
      }
      // if path is null
      if (path == null) {
        this.$router.push({ path: this.navigator.path });
        return
      }

      this.$router.push({ path: path });
    }
  }
}
</script>

<style scoped>

</style>
