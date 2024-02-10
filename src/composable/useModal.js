import { ref } from 'vue'
export function useModal(title='') {
    const ModalTitle = ref('');
    const isActive = ref(false);
    const openModal = () => {
        ModalTitle.value = `Insert ${title}`
        isActive.value = true

    }

    const closeModal = () => {
        isActive.value = false

    }


    return {
        ModalTitle,
        isActive,
        openModal,
        closeModal
    }

}
