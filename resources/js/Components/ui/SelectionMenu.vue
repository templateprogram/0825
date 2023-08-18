<script setup>
import { ref, watch,onMounted} from 'vue';
const wantSelect=ref(false);
const selectionParent=ref(null);
onMounted(() => {
    [...selectionParent.value.querySelectorAll(`li`)].forEach(element => {
        element.addEventListener('click',()=>{
            wantSelect.value=!wantSelect.value;
        })
    });
});
</script>

<template>
    <div>

    
        <div @click="wantSelect=!wantSelect">
            <slot name="input" />
        </div>
        <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
            <div  v-show="wantSelect" class="p-4 mt-2 bg-gray-100 border rounded dark:border-gray-700 dark:bg-gray-600">
                <ul class="space-y-10" ref="selectionParent" >
                    <slot name="list" />
                    <!-- <p class="p-4" v-if="filterData.length === 0">找不到結果</p> -->
                </ul>
            </div>
        </Transition>
    </div>
</template>

<style scoped></style>