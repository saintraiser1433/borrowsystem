import { ref } from 'vue'

export const headerInventory = ref([
    { title: 'Image', align: 'center', key: 'img_path' },
    // { title: 'path', align: 'center', key: 'img_path'},
    { title: 'Asset Tag', align: 'start', key: 'asset_tag', sortable: true },
    { title: 'Item Name', align: 'start', key: 'item_name', sortable: true },
    { title: 'Category', align: 'start', key: 'category_id', sortable: true },
    { title: 'Description', align: 'start', key: 'description', sortable: true },
    { title: 'Model', align: 'start', key: 'item_model', sortable: true },
    { title: 'Condition', align: 'start', key: 'item_condition', sortable: true },
    { title: 'Status', align: 'start', key: 'status', sortable: true },
    { title: 'Actions', align: 'start', key: 'actions', sortable: true }

]);

export const headerCategory = ref([
    { title: 'Category Description', align: 'center', key: 'description' },
    { title: 'Status', align: 'center', key: 'status' },
    { title: 'Actions', align: 'start', key: 'actions', sortable: true }
]);




