<!-- ProductTable.vue -->

<template>
    <div>
      <table class="collection_table">
        <thead>
          <tr>
            <th v-for="header in headers" :key="header">{{ header }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in data" :key="index">
            <td v-for="(value, key) in item" :key="key">{{ value }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script>
  export default {
    props: {
      headers: Array,
      data: Array,
    },
  };
  </script>

  <style scoped>
  /* Component-specific styles go here */
  .collection_table {
    border-collapse: collapse;
    margin: 20px;
  }

  .collection_table th,
  .collection_table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
  }

  .collection_table th {
    background-color: #f2f2f2;
  }
  </style>
